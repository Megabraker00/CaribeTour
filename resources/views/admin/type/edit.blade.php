@extends('adminlte::page')

@section('title', 'Editar tipo')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Editar tipo</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.types.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('admin.types.update', $type) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" maxlength="50"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $type->name) }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="typeable">Aplica a (modelo) <span class="text-danger">*</span></label>
                    <select name="typeable" id="typeable" class="form-control @error('typeable') is-invalid @enderror" required>
                        @foreach ($typeableOptions as $class => $label)
                            <option value="{{ $class }}"
                                {{ (string) old('typeable', $type->typeable) === $class ? 'selected' : '' }}>
                                {{ $label }} ({{ str_replace('App\\Models\\', '', $class) }})
                            </option>
                        @endforeach
                        @unless (array_key_exists($type->typeable, $typeableOptions))
                            <option value="{{ $type->typeable }}" {{ old('typeable', $type->typeable) === $type->typeable ? 'selected' : '' }}>
                                {{ $type->typeable }} (valor actual, no listado)
                            </option>
                        @endunless
                    </select>
                    @error('typeable')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Si el tipo ya está en uso, no podrás cambiar el modelo.</small>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@stop
