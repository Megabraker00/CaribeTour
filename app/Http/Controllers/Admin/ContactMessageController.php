<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        return view('admin.contact-message.index');
    }

    public function show(ContactMessage $contactMessage): View
    {
        $contactMessage->load('product');
        $contactMessage->markAsRead();

        return view('admin.contact-message.show', compact('contactMessage'));
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Mensaje eliminado correctamente.');
    }
}
