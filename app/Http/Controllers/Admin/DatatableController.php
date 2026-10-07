<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Status;
use App\Support\ProductCatalog;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class DatatableController extends Controller
{
    public function bookings()
    {
        $query = Booking::query()
            ->with(['client', 'statusRecord', 'itineraries' => function ($q) {
                $q->orderBy('booking_itinerary.itinerary_order')->with(['segments' => function ($sq) {
                    $sq->orderBy('sort_order');
                }]);
            }])
            ->select('bookings.*');

        return DataTables::eloquent($query)
            ->addColumn('titular', function (Booking $booking) {
                $titular = trim(($booking->client->name ?? '').' '.($booking->client->last_name ?? ''));

                return $titular !== '' ? $titular : '—';
            })
            ->addColumn('f_salida', function (Booking $booking) {
                $firstItinerary = $booking->itineraries->first();
                $firstSegment = $firstItinerary?->segments?->sortBy('sort_order')->first();
                if ($firstSegment?->departure_date) {
                    return $firstSegment->departure_date->format('d/m/Y');
                }

                return '—';
            })
            ->addColumn('total_amount', function (Booking $booking) {
                $totalPrice = $booking->total_price !== null ? (float) $booking->total_price : 0;

                return number_format($totalPrice, 2, ',', '.').' '.($booking->currency ?? 'EUR');
            })
            ->addColumn('status_name', function (Booking $booking) {
                $name = $booking->statusRecord->name ?? (string) $booking->status_id;
                $slug = $booking->statusRecord->slug ?? null;

                return '<span class="badge '.self::bookingStatusBadgeClass($slug).'">'.e($name).'</span>';
            })
            ->rawColumns(['status_name'])
            ->filterColumn('titular', function ($query, $keyword) {
                $query->whereHas('client', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('status_name', function ($query, $keyword) {
                $query->whereHas('statusRecord', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('total_amount', function ($query, $keyword) {
                $query->where('total_price', 'like', "%{$keyword}%");
            })
            ->filterColumn('f_salida', function ($query, $keyword) {
                // Computed from segments; keep global search from breaking the query.
            })
            ->orderColumn('total_amount', 'total_price $1')
            ->orderColumn('status_name', 'status_id $1')
            ->orderColumn('titular', 'client_id $1')
            ->orderColumn('f_salida', 'id $1')
            ->toJson();
    }

    public function invoices()
    {
        $query = Invoice::query()
            ->with(['booking', 'rectifiedInvoice'])
            ->select('invoices.*');

        return DataTables::eloquent($query)
            ->addColumn('booking_ref', function (Invoice $invoice) {
                return $invoice->booking->external_ref ?? '—';
            })
            ->addColumn('kind', function (Invoice $invoice) {
                return $invoice->isCreditNote() ? 'Abono' : 'Factura';
            })
            ->addColumn('amount_label', function (Invoice $invoice) {
                return number_format((float) $invoice->total_amount, 2, ',', '.').' '.($invoice->currency ?? 'EUR');
            })
            ->addColumn('issued_on', function (Invoice $invoice) {
                return $invoice->issue_date?->format('d/m/Y') ?? '—';
            })
            ->filterColumn('booking_ref', function ($query, $keyword) {
                $query->whereHas('booking', function ($q) use ($keyword) {
                    $q->where('external_ref', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('kind', function ($query, $keyword) {
                $needle = mb_strtolower($keyword);
                if (str_contains('abono', $needle) || str_contains($needle, 'abo')) {
                    $query->whereNotNull('rectifies_invoice_id');
                } elseif (str_contains('factura', $needle) || str_contains($needle, 'fac')) {
                    $query->whereNull('rectifies_invoice_id');
                }
            })
            ->filterColumn('amount_label', function ($query, $keyword) {
                $query->where('total_amount', 'like', "%{$keyword}%");
            })
            ->filterColumn('issued_on', function ($query, $keyword) {
                $query->where('issue_date', 'like', "%{$keyword}%");
            })
            ->orderColumn('booking_ref', 'booking_id $1')
            ->orderColumn('kind', 'rectifies_invoice_id $1')
            ->orderColumn('amount_label', 'total_amount $1')
            ->orderColumn('issued_on', 'issue_date $1')
            ->toJson();
    }

    public function clients()
    {
        $query = Client::query()->select('id', 'name', 'last_name', 'dni_passport');

        return DataTables::eloquent($query)->toJson();
    }

    public function blogs()
    {
        $query = Blog::query()
            ->with(['statusRecord', 'createdUser'])
            ->select('blogs.*');

        return DataTables::eloquent($query)
            ->addColumn('status_name', function (Blog $blog) {
                return $blog->statusRecord->name ?? (string) $blog->status_id;
            })
            ->addColumn('author', function (Blog $blog) {
                return $blog->createdUser->name ?? '—';
            })
            ->filterColumn('status_name', function ($query, $keyword) {
                $query->whereHas('statusRecord', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('author', function ($query, $keyword) {
                $query->whereHas('createdUser', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->orderColumn('status_name', 'status_id $1')
            ->orderColumn('author', 'created_user_id $1')
            ->toJson();
    }

    public function tours()
    {
        return $this->catalogProducts('tours');
    }

    public function catalogProducts(string $kind)
    {
        $catalog = ProductCatalog::fromKind($kind);

        $minByProduct = DB::table('itineraries')
            ->selectRaw('product_id, MIN(price + taxes) as min_total')
            ->groupBy('product_id');

        $query = Product::query()
            ->where('products.type_id', $catalog['type_id'])
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('statuses', function ($join) {
                $join->on('products.status_id', '=', 'statuses.id')
                    ->where('statuses.statusable', Product::class);
            })
            ->leftJoinSub($minByProduct, 'it_min', function ($join) {
                $join->on('products.id', '=', 'it_min.product_id');
            })
            ->select(
                'products.id',
                'products.name as name',
                'categories.name as category',
                'products.status_id',
                'statuses.name as status_name',
                DB::raw('ROUND(it_min.min_total, 2) as precio')
            );

        return DataTables::of($query)
            ->editColumn('status_name', function ($row) {
                if ($row->status_name === null || $row->status_name === '') {
                    return '— (id '.$row->status_id.')';
                }

                return $row->status_name;
            })
            ->filterColumn('category', function ($query, $keyword) {
                $query->where('categories.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('status_name', function ($query, $keyword) {
                $query->where('statuses.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('precio', function ($query, $keyword) {
                $query->whereRaw('ROUND(it_min.min_total, 2) like ?', ["%{$keyword}%"]);
            })
            ->orderColumn('category', 'categories.name $1')
            ->orderColumn('status_name', 'statuses.name $1')
            ->orderColumn('precio', 'it_min.min_total $1')
            ->toJson();
    }

    public function tourItinerarys($productId)
    {
        $query = Itinerary::query()
            ->where('product_id', $productId)
            ->with([
                'segments' => function ($q) {
                    $q->orderBy('sort_order')->with(['departureTerminal', 'arrivalTerminal']);
                },
            ]);

        return DataTables::eloquent($query)
            ->addColumn('departure_date', function (Itinerary $itinerary) {
                $date = $itinerary->segments->first()?->departure_date;

                return $date ? $date->format('Y-m-d H:i:s') : '';
            })
            ->addColumn('departure_t', function (Itinerary $itinerary) {
                return [
                    'name' => $itinerary->segments->first()?->departureTerminal?->name ?? '—',
                ];
            })
            ->addColumn('arrival_date', function (Itinerary $itinerary) {
                $date = $itinerary->segments->first()?->arrival_date;

                return $date ? $date->format('Y-m-d H:i:s') : '';
            })
            ->addColumn('arrival_t', function (Itinerary $itinerary) {
                return [
                    'name' => $itinerary->segments->first()?->arrivalTerminal?->name ?? '—',
                ];
            })
            ->addColumn('total_price', function (Itinerary $itinerary) {
                return $itinerary->fullPrice();
            })
            ->toJson();
    }

    private static function bookingStatusBadgeClass(?string $slug): string
    {
        return match ($slug) {
            Status::BOOKING_PAID => 'bg-success',
            Status::BOOKING_CANCELLED => 'bg-danger',
            Status::BOOKING_PENDING => 'bg-secondary',
            default => 'bg-secondary',
        };
    }
}
