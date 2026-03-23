@extends('adminlte::page')

@section('title', 'Editar estado')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Editar estado</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.statuses.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('admin.statuses.update', $status) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" maxlength="50"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $status->name) }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="statusable">Aplica a (modelo) <span class="text-danger">*</span></label>
                    <select name="statusable" id="statusable" class="form-control @error('statusable') is-invalid @enderror" required>
                        @foreach ($statusableOptions as $class => $label)
                            <option value="{{ $class }}"
                                {{ (string) old('statusable', $status->statusable) === $class ? 'selected' : '' }}>
                                {{ $label }} ({{ str_replace('App\\Models\\', '', $class) }})
                            </option>
                        @endforeach
                        @unless (array_key_exists($status->statusable, $statusableOptions))
                            <option value="{{ $status->statusable }}" {{ old('statusable', $status->statusable) === $status->statusable ? 'selected' : '' }}>
                                {{ $status->statusable }} (valor actual, no listado)
                            </option>
                        @endunless
                    </select>
                    @error('statusable')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Si el estado ya está en uso, no podrás cambiar el modelo.</small>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@stop
