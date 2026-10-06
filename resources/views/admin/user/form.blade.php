@php
    $user = $user ?? null;
    $isEdit = $user !== null;
@endphp
<form action="{{ $action }}" method="POST" data-disable-on-submit>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <div class="form-group">
        <label for="name">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" maxlength="255"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user?->name) }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="email">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" id="email" maxlength="255"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user?->email) }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @php
        $selectedRole = old('role', $user?->role?->value);
        $lockRole = $isEdit && $user->isLastAdmin();
    @endphp
    <div class="form-group">
        <label for="role">Rol <span class="text-danger">*</span></label>
        @if ($lockRole)
            <input type="hidden" name="role" value="{{ $user->role->value }}">
        @endif
        <select id="role" class="form-control @error('role') is-invalid @enderror"
            @unless ($lockRole) name="role" required @endunless @disabled($lockRole)>
            <option value="">Selecciona un rol</option>
            @foreach (\App\Enums\UserRole::cases() as $roleOption)
                <option value="{{ $roleOption->value }}" @selected($selectedRole === $roleOption->value)>
                    {{ $roleOption->label() }}
                </option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">
            Administrador: acceso completo y gestión de usuarios.
            Agente: puede crear y modificar datos.
            Solo lectura: solo consulta.
        </small>
        @if ($lockRole)
            <small class="form-text text-warning">Es el único administrador. Asigna ese rol a otra persona antes de cambiarlo.</small>
        @endif
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="password">Contraseña @unless($isEdit)<span class="text-danger">*</span>@endunless</label>
            <input type="password" name="password" id="password" minlength="8"
                class="form-control @error('password') is-invalid @enderror"
                @unless($isEdit) required @endunless autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if ($isEdit)
                <small class="form-text text-muted">Déjala vacía para no cambiarla.</small>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label for="password_confirmation">Confirmar contraseña @unless($isEdit)<span class="text-danger">*</span>@endunless</label>
            <input type="password" name="password_confirmation" id="password_confirmation" minlength="8"
                class="form-control" @unless($isEdit) required @endunless autocomplete="new-password">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Actualizar' : 'Guardar' }}</button>
</form>
@include('admin.partials.disable-on-submit')
