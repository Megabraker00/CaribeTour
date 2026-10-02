@php
    $supplier = $supplier ?? null;
    $isEdit = $supplier !== null;
@endphp
<form action="{{ $action }}" method="POST" data-disable-on-submit>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <div class="form-group">
        <label for="name">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" maxlength="100"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $supplier?->name) }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="status_id">Estado <span class="text-danger">*</span></label>
        <select name="status_id" id="status_id" class="form-control @error('status_id') is-invalid @enderror" required>
            <option value="">— Selecciona —</option>
            @foreach ($statuses as $st)
                <option value="{{ $st->id }}" {{ (string) old('status_id', $supplier?->status_id) === (string) $st->id ? 'selected' : '' }}>
                    {{ $st->name }}
                </option>
            @endforeach
        </select>
        @error('status_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Actualizar' : 'Guardar' }}</button>
</form>
@include('admin.partials.disable-on-submit')
