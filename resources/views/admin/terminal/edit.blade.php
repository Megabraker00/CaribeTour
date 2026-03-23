@extends('adminlte::page')

@section('title', 'Editar terminal')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Editar terminal</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.terminals.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('admin.terminals.update', $terminal) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" maxlength="100"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $terminal->name) }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="address">Dirección</label>
                    <input type="text" name="address" id="address" maxlength="255"
                        class="form-control @error('address') is-invalid @enderror"
                        value="{{ old('address', $terminal->address) }}" placeholder="Opcional">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="parent_id">Terminal padre</label>
                    <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                        <option value="">— Ninguno —</option>
                        @foreach ($parentOptions as $p)
                            <option value="{{ $p->id }}"
                                {{ (string) old('parent_id', $terminal->parent_id) === (string) $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">No puedes elegir este terminal ni sus hijos como padre.</small>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@stop
