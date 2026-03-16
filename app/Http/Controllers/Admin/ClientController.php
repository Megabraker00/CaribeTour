<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.client.index');
    }

    public function show(Client $client)
    {
        $client->load(['statusRecord', 'bookings.statusRecord']);

        return view('admin.client.show', ['client' => $client]);
    }

    public function edit(Client $client)
    {
        $statuses = Status::where('statusable', Client::class)->orderBy('name')->get();

        return view('admin.client.edit', [
            'client' => $client,
            'statuses' => $statuses,
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'date_of_birth' => 'nullable|date',
            'dni_passport' => 'required|string|max:20',
            'nationality' => 'nullable|string|max:20',
            'status_id' => 'required|exists:statuses,id',
        ]);

        $client->update($validated);

        return redirect()
            ->route('admin.client.show', $client)
            ->with('success', 'Cliente actualizado correctamente.');
    }
}
