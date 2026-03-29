@extends('adminlte::page')

@section('title', 'Editar segmento')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Editar segmento</h1>
            <p class="text-muted mb-0">
                Itinerario #{{ $itinerary->id }} — orden {{ $segment->sort_order }} (id {{ $segment->id }})
            </p>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.itineraries.segments.index', $itinerary) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a segmentos
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('admin.itineraries.segments.update', [$itinerary, $segment]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="sort_order">Orden</label>
                        <input type="number" name="sort_order" id="sort_order" min="1" required
                            class="form-control @error('sort_order') is-invalid @enderror"
                            value="{{ old('sort_order', $segment->sort_order) }}">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="type_id">Tipo</label>
                        <select name="type_id" id="type_id" class="form-control @error('type_id') is-invalid @enderror" required>
                            @foreach ($segmentTypes as $t)
                                <option value="{{ $t->id }}"
                                    {{ (string) old('type_id', $segment->type_id) === (string) $t->id ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="departure_date">Salida</label>
                        <input type="datetime-local" name="departure_date" id="departure_date" required
                            class="form-control @error('departure_date') is-invalid @enderror"
                            value="{{ old('departure_date', $segment->departure_date?->format('Y-m-d\TH:i')) }}">
                        @error('departure_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="arrival_date">Llegada</label>
                        <input type="datetime-local" name="arrival_date" id="arrival_date" required
                            class="form-control @error('arrival_date') is-invalid @enderror"
                            value="{{ old('arrival_date', $segment->arrival_date?->format('Y-m-d\TH:i')) }}">
                        @error('arrival_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="departure_terminal_id">Terminal salida</label>
                        <select name="departure_terminal_id" id="departure_terminal_id"
                            class="form-control @error('departure_terminal_id') is-invalid @enderror" required>
                            <option value="">—</option>
                            @foreach ($terminals as $term)
                                <option value="{{ $term->id }}"
                                    {{ (string) old('departure_terminal_id', $segment->departure_terminal_id) === (string) $term->id ? 'selected' : '' }}>
                                    {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('departure_terminal_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="arrival_terminal_id">Terminal llegada</label>
                        <select name="arrival_terminal_id" id="arrival_terminal_id"
                            class="form-control @error('arrival_terminal_id') is-invalid @enderror" required>
                            <option value="">—</option>
                            @foreach ($terminals as $term)
                                <option value="{{ $term->id }}"
                                    {{ (string) old('arrival_terminal_id', $segment->arrival_terminal_id) === (string) $term->id ? 'selected' : '' }}>
                                    {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('arrival_terminal_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="origin">Origen (opcional)</label>
                        <input type="text" name="origin" id="origin" maxlength="255" class="form-control"
                            value="{{ old('origin', $segment->origin) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="destination">Destino (opcional)</label>
                        <input type="text" name="destination" id="destination" maxlength="255" class="form-control"
                            value="{{ old('destination', $segment->destination) }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>
        </div>
    </div>
@stop
