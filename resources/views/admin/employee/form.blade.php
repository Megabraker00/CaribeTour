@php
    $employee = $employee ?? null;
    $isEdit = $employee !== null;
@endphp
@if ($positions->isEmpty())
    <div class="alert alert-warning">No hay cargos. Ejecuta los seeders o crea un cargo en la tabla <code>positions</code> antes de dar de alta empleados.</div>
@endif
<form action="{{ $action }}" method="POST" data-disable-on-submit>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="name">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" maxlength="100"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $employee?->name) }}" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="last_name">Apellidos <span class="text-danger">*</span></label>
            <input type="text" name="last_name" id="last_name" maxlength="100"
                class="form-control @error('last_name') is-invalid @enderror"
                value="{{ old('last_name', $employee?->last_name) }}" required>
            @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="email_personal">Email personal <span class="text-danger">*</span></label>
            <input type="email" name="email_personal" id="email_personal" maxlength="100"
                class="form-control @error('email_personal') is-invalid @enderror"
                value="{{ old('email_personal', $employee?->email_personal) }}" required>
            @error('email_personal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="email_company">Email de empresa <span class="text-danger">*</span></label>
            <input type="email" name="email_company" id="email_company" maxlength="100"
                class="form-control @error('email_company') is-invalid @enderror"
                value="{{ old('email_company', $employee?->email_company) }}" required>
            @error('email_company')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="dni_passport">DNI o pasaporte <span class="text-danger">*</span></label>
            <input type="text" name="dni_passport" id="dni_passport" maxlength="20"
                class="form-control @error('dni_passport') is-invalid @enderror"
                value="{{ old('dni_passport', $employee?->dni_passport) }}" required>
            @error('dni_passport')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="phone">Teléfono</label>
            <input type="text" name="phone" id="phone" maxlength="20"
                class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $employee?->phone) }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="position_id">Cargo <span class="text-danger">*</span></label>
            <select name="position_id" id="position_id" class="form-control @error('position_id') is-invalid @enderror" required>
                <option value="">— Selecciona —</option>
                @foreach ($positions as $position)
                    <option value="{{ $position->id }}" {{ (string) old('position_id', $employee?->position_id) === (string) $position->id ? 'selected' : '' }}>
                        {{ $position->name }}
                    </option>
                @endforeach
            </select>
            @error('position_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="status_id">Estado <span class="text-danger">*</span></label>
            <select name="status_id" id="status_id" class="form-control @error('status_id') is-invalid @enderror" required>
                <option value="">— Selecciona —</option>
                @foreach ($statuses as $st)
                    <option value="{{ $st->id }}" {{ (string) old('status_id', $employee?->status_id) === (string) $st->id ? 'selected' : '' }}>
                        {{ $st->name }}
                    </option>
                @endforeach
            </select>
            @error('status_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn-primary" {{ $positions->isEmpty() ? 'disabled' : '' }}>{{ $isEdit ? 'Actualizar' : 'Guardar' }}</button>
</form>
@include('admin.partials.disable-on-submit')
