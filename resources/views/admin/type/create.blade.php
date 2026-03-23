@extends('adminlte::page')

@section('title', 'Nuevo tipo')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Nuevo tipo</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.types.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('admin.types.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" maxlength="50"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required autofocus placeholder="Ej.: Tour premium">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="typeable">Aplica a (modelo) <span class="text-danger">*</span></label>
                    <select name="typeable" id="typeable" class="form-control @error('typeable') is-invalid @enderror" required>
                        <option value="">— Selecciona —</option>
                        @foreach ($typeableOptions as $class => $label)
                            <option value="{{ $class }}" {{ old('typeable') === $class ? 'selected' : '' }}>
                                {{ $label }} ({{ str_replace('App\\Models\\', '', $class) }})
                            </option>
                        @endforeach
                    </select>
                    @error('typeable')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Se guarda el nombre de clase completo en <code>types.typeable</code> (relación polimórfica).</small>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@stop
