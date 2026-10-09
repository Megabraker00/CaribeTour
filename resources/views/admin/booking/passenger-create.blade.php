@extends('adminlte::page')

@section('title', 'Añadir pasajero')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm">
        <h1>Añadir pasajero</h1>
    </div>
    <div class="col-sm text-right">
        <a href="{{ route('admin.booking.show', $booking) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la reserva
        </a>
    </div>
</div>
@stop

@section('content')
    @include('admin.partials.flash')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Nuevo pasajero — reserva {{ $booking->external_ref ?? '#'.$booking->id }}</h3>
        </div>
        <div class="card-body">
            <p class="text-muted">Se reserva una plaza y se suma al total la tarifa por persona de la salida, según la edad.</p>

            <form action="{{ route('admin.booking.passengers.store', $booking) }}" method="POST" data-disable-on-submit>
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" maxlength="100"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_name">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name" maxlength="100"
                                class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dni_passport">DNI / Pasaporte <span class="text-danger">*</span></label>
                            <input type="text" name="dni_passport" id="dni_passport" maxlength="20"
                                class="form-control @error('dni_passport') is-invalid @enderror"
                                value="{{ old('dni_passport') }}" required>
                            @error('dni_passport')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nationality">Nacionalidad <span class="text-danger">*</span></label>
                            <input type="text" name="nationality" id="nationality" maxlength="20"
                                class="form-control @error('nationality') is-invalid @enderror"
                                value="{{ old('nationality') }}" required>
                            @error('nationality')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender">Género <span class="text-danger">*</span></label>
                            @php $currentGender = strtolower((string) old('gender', 'male')); @endphp
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="male" {{ $currentGender === 'male' ? 'selected' : '' }}>Hombre</option>
                                <option value="female" {{ $currentGender === 'female' ? 'selected' : '' }}>Mujer</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth">Fecha de nacimiento <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth') }}" required>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Añadir pasajero</button>
            </form>
        </div>
    </div>
    @include('admin.partials.disable-on-submit')
@stop
