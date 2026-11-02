<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    public function show(Request $request): Response
    {
        $slug = old('product', $request->query('tour'));
        $tour = $this->publicTour(is_string($slug) ? $slug : null);

        return response()
            ->view('contacto', [
                'subject' => old('subject', $tour?->name ?? ''),
                'productSlug' => old('product', $tour?->slug ?? ''),
            ])
            ->header(
                'Permissions-Policy',
                'local-network=(self "https://www.google.com" "https://maps.google.com"), loopback-network=(self "https://www.google.com" "https://maps.google.com")'
            );
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:5000'],
            'product' => ['nullable', 'string', 'max:255'],
        ]);

        $tour = $this->publicTour($data['product'] ?? null);

        ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'product_id' => $tour?->id,
        ]);

        return redirect()
            ->route('contacto')
            ->with('success', 'Hemos recibido tu mensaje. Te responderemos lo antes posible.');
    }

    private function publicTour(?string $slug): ?Product
    {
        if ($slug === null || $slug === '') {
            return null;
        }

        return Product::query()->publicVisibleTour()->where('slug', $slug)->first();
    }
}
