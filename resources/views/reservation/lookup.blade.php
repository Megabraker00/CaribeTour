@extends('front_template')
@section('title', 'Consultar reserva')
@section('description', 'Consulta el estado de tu reserva en CaribeTour con el localizador y el correo del titular.')
@section('robots', 'noindex, nofollow')

@section('content')
    <section class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <h1 class="h3 mb-4">Consultar mi reserva</h1>

                <form action="{{ route('reservation.lookup.submit') }}" method="POST" class="card shadow-sm mb-4" data-disable-on-submit>
                    @csrf
                    <div class="card-body">
                        <p class="text-muted">Todos los campos son obligatorios.</p>
                        <div class="mb-3">
                            <label for="external_ref" class="form-label">Localizador</label>
                            <input type="text" class="form-control @error('external_ref') is-invalid @enderror" id="external_ref" name="external_ref" value="{{ old('external_ref') }}" required @error('external_ref') aria-invalid="true" aria-describedby="external_ref-error" @enderror>
                            @error('external_ref')
                                <div id="external_ref-error" class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo del titular</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                            @error('email')
                                <div id="email-error" class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Consultar</button>
                    </div>
                </form>

                @isset($booking)
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <strong>Reserva {{ $booking->external_ref }}</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Estado:</strong> {{ $booking->statusRecord->name ?? $booking->status_id }}</p>
                            <p><strong>Titular:</strong> {{ $booking->client }}</p>
                            <p><strong>Total:</strong> {{ $booking->formattedPassengersTotal() }} {{ $booking->currency }}</p>
                            <p><strong>Pasajeros:</strong></p>
                            <ul>
                                @foreach ($booking->passengers as $passenger)
                                    <li>{{ $passenger }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </section>
@endsection
