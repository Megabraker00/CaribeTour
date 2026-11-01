<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    public function show(): Response
    {
        return response()
            ->view('contacto')
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
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create($data);

        return redirect()
            ->route('contacto')
            ->with('success', 'Hemos recibido tu mensaje. Te responderemos lo antes posible.');
    }
}
