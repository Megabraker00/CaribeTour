<x-mail::message>
# Reserva confirmada

Hola {{ $booking->client?->name }},

Tu reserva **{{ $booking->external_ref }}** se ha pagado correctamente.

- Total: {{ number_format((float) $booking->total_price, 2, ',', '.') }} {{ $booking->currency }}
- Pasajeros: {{ $booking->passengers->count() }}

Conserva este localizador para consultar tu reserva.

<x-mail::button :url="route('reservation.lookup')">
Consultar mi reserva
</x-mail::button>

Gracias por viajar con {{ config('app.name') }}.
</x-mail::message>
