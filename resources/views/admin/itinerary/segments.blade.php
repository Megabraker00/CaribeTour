@extends('adminlte::page')

@section('title', 'Segmentos del itinerario')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Segmentos del itinerario</h1>
            <p class="text-muted mb-0">
                Itinerario #{{ $itinerary->id }} — {{ $itinerary->product->name ?? 'Tour' }}
            </p>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.tour.edit', $itinerary->product_id) }}#tour-itineraries" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al tour
            </a>
        </div>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <p class="text-muted">
        Orden de los tramos: <code>sort_order</code> ascendente. El primer y último segmento definen la duración mostrada al cliente.
    </p>

    <div class="card card-outline card-primary mb-4">
        <div class="card-header">
            <strong>Añadir segmento</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.itineraries.segments.store', $itinerary) }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="new_sort_order">Orden</label>
                        <input type="number" name="sort_order" id="new_sort_order" min="1" required
                            class="form-control @error('sort_order') is-invalid @enderror"
                            value="{{ old('sort_order', $nextSortOrder) }}">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="new_type_id">Tipo</label>
                        <select name="type_id" id="new_type_id" class="form-control @error('type_id') is-invalid @enderror" required>
                            <option value="">— Selecciona —</option>
                            @foreach ($segmentTypes as $t)
                                <option value="{{ $t->id }}" {{ (string) old('type_id') === (string) $t->id ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="new_departure_date">Salida</label>
                        <input type="datetime-local" name="departure_date" id="new_departure_date" required
                            class="form-control @error('departure_date') is-invalid @enderror"
                            value="{{ old('departure_date') }}">
                        @error('departure_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="new_arrival_date">Llegada</label>
                        <input type="datetime-local" name="arrival_date" id="new_arrival_date" required
                            class="form-control @error('arrival_date') is-invalid @enderror"
                            value="{{ old('arrival_date') }}">
                        @error('arrival_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="new_departure_terminal_id">Terminal salida</label>
                        <select name="departure_terminal_id" id="new_departure_terminal_id"
                            class="form-control @error('departure_terminal_id') is-invalid @enderror" required>
                            <option value="">—</option>
                            @foreach ($terminals as $term)
                                <option value="{{ $term->id }}" {{ (string) old('departure_terminal_id') === (string) $term->id ? 'selected' : '' }}>
                                    {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('departure_terminal_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="new_arrival_terminal_id">Terminal llegada</label>
                        <select name="arrival_terminal_id" id="new_arrival_terminal_id"
                            class="form-control @error('arrival_terminal_id') is-invalid @enderror" required>
                            <option value="">—</option>
                            @foreach ($terminals as $term)
                                <option value="{{ $term->id }}" {{ (string) old('arrival_terminal_id') === (string) $term->id ? 'selected' : '' }}>
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
                        <label for="new_origin">Origen (opcional)</label>
                        <input type="text" name="origin" id="new_origin" maxlength="255" class="form-control" value="{{ old('origin') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="new_destination">Destino (opcional)</label>
                        <input type="text" name="destination" id="new_destination" maxlength="255" class="form-control" value="{{ old('destination') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Añadir segmento</button>
            </form>
        </div>
    </div>

    <div class="card card-outline card-secondary">
        <div class="card-header">
            <strong>Segmentos</strong> <span class="text-muted small">(ordenados por orden ascendente)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width:72px">Orden</th>
                            <th>Tipo</th>
                            <th>Salida</th>
                            <th>Terminal salida</th>
                            <th>Llegada</th>
                            <th>Terminal llegada</th>
                            <th>Origen / destino</th>
                            <th class="text-right text-nowrap" style="width:1%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($itinerary->segments as $segment)
                            <tr>
                                <td class="align-middle"><strong>{{ $segment->sort_order }}</strong></td>
                                <td class="align-middle">{{ $segment->type?->name ?? '—' }}</td>
                                <td class="align-middle text-nowrap small">
                                    {{ $segment->departure_date?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                <td class="align-middle small">{{ $segment->departureTerminal->name ?? '—' }}</td>
                                <td class="align-middle text-nowrap small">
                                    {{ $segment->arrival_date?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                <td class="align-middle small">{{ $segment->arrivalTerminal->name ?? '—' }}</td>
                                <td class="align-middle small text-muted">
                                    @if ($segment->origin || $segment->destination)
                                        {{ $segment->origin ?? '—' }} → {{ $segment->destination ?? '—' }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="align-middle text-right text-nowrap">
                                    <a href="{{ route('admin.itineraries.segments.edit', [$itinerary, $segment]) }}"
                                        class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-pencil-alt"></i> Editar
                                    </a>
                                    <form action="{{ route('admin.itineraries.segments.destroy', [$itinerary, $segment]) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Eliminar este segmento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i> Borrar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No hay segmentos. Usa el formulario superior para añadir el primero.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
