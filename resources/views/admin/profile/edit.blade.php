@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Perfil</h1>
        </div>
    </div>
@stop

@section('content')
    @include('admin.partials.flash')

    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" data-disable-on-submit>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" maxlength="255"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" maxlength="255"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <input type="text" class="form-control" value="{{ $user->role?->label() }}" readonly>
                    <small class="form-text text-muted">El rol lo cambia un administrador desde Usuarios.</small>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Contraseña</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.password') }}" method="POST" data-disable-on-submit>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">Contraseña actual <span class="text-danger">*</span></label>
                    <input type="password" name="current_password" id="current_password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        autocomplete="current-password">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Nueva contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" minlength="8"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password_confirmation">Confirmar contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" minlength="8"
                            class="form-control" autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
            </form>
            @include('admin.partials.disable-on-submit')
        </div>
    </div>
@stop
