@extends('adminlte::page')

@section('title', 'Nuevo estado')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Nuevo estado</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.statuses.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('admin.statuses.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" maxlength="50"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required autofocus placeholder="Ej.: Pendiente de revisión">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="statusable">Aplica a (modelo) <span class="text-danger">*</span></label>
                    <select name="statusable" id="statusable" class="form-control @error('statusable') is-invalid @enderror" required>
                        <option value="">— Selecciona —</option>
                        @foreach ($statusableOptions as $class => $label)
                            <option value="{{ $class }}" {{ old('statusable') === $class ? 'selected' : '' }}>
                                {{ $label }} ({{ str_replace('App\\Models\\', '', $class) }})
                            </option>
                        @endforeach
                    </select>
                    @error('statusable')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Se guarda el FQCN en <code>statuses.statusable</code> (relación polimórfica).</small>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@stop
