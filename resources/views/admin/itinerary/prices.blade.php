@extends('adminlte::page')

@section('title', 'Tarifas por pasajero')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Tarifas por tipo de pasajero</h1>
            <p class="text-muted mb-0">
                Itinerario #{{ $itinerary->id }} — {{ $itinerary->product->name ?? 'Tour' }}
            </p>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.tour.edit', $itinerary->product_id) }}#tour-itineraries" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al tour
            </a>
        </div>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-info mb-3">
        <div class="card-body py-3">
            <strong>Tarifa base del itinerario</strong> (se usa si no defines precio para un tipo):
            <span class="ml-2">{{ number_format((float) $itinerary->price, 2, ',', '.') }} €</span>
            + <span>{{ number_format((float) $itinerary->taxes, 2, ',', '.') }} €</span> tasas
            = <strong>{{ number_format($itinerary->fullPrice(), 2, ',', '.') }} €</strong> total por persona.
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('admin.itineraries.prices.update', $itinerary) }}" method="POST">
                @csrf
                @method('PUT')

                <p class="text-muted">
                    Deja <strong>precio y tasas vacíos</strong> para ese tipo de pasajero: se aplicará la tarifa base del itinerario.
                </p>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo de pasajero</th>
                                <th style="width: 200px">Precio (€)</th>
                                <th style="width: 200px">Tasas (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($passengerTypes as $type)
                                @php
                                    $row = $pricesByType->get($type->id);
                                @endphp
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $type->name }}</strong>
                                        <br><small class="text-muted">ID tipo {{ $type->id }}</small>
                                    </td>
                                    <td>
                                        <input type="number"
                                            name="prices[{{ $type->id }}][price]"
                                            class="form-control @error('prices.'.$type->id) is-invalid @enderror @error('prices.'.$type->id.'.price') is-invalid @enderror"
                                            step="0.01" min="0" placeholder="—"
                                            value="{{ old('prices.'.$type->id.'.price', $row?->price) }}">
                                    </td>
                                    <td>
                                        <input type="number"
                                            name="prices[{{ $type->id }}][taxes]"
                                            class="form-control @error('prices.'.$type->id) is-invalid @enderror @error('prices.'.$type->id.'.taxes') is-invalid @enderror"
                                            step="0.01" min="0" placeholder="—"
                                            value="{{ old('prices.'.$type->id.'.taxes', $row?->taxes) }}">
                                    </td>
                                </tr>
                                @error('prices.'.$type->id)
                                    <tr>
                                        <td colspan="3" class="border-0 pt-0">
                                            <div class="text-danger small">{{ $message }}</div>
                                        </td>
                                    </tr>
                                @enderror
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($passengerTypes->isEmpty())
                    <div class="alert alert-warning">
                        No hay tipos de pasajero en la tabla <code>types</code> con <code>typeable = Passenger</code>.
                        Ejecuta los seeders o crea esos tipos en el admin.
                    </div>
                @else
                    <button type="submit" class="btn btn-primary">Guardar tarifas</button>
                @endif
            </form>
        </div>
    </div>
@stop
