@extends('adminlte::page')

@section('title', 'Tarifas por pasajero')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Tarifas por tipo de pasajero</h1>
            <p class="text-muted mb-0">
                Itinerario #{{ $itinerary->id }} — {{ $itinerary->product->name ?? 'Tour' }}
            </p>
        </div>
        <div class="col-sm text-right">
            <a href="{{ \App\Support\ProductCatalog::editUrl($itinerary->product) }}#tour-itineraries" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al producto
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

    <div class="card card-outline card-info mb-3">
        <div class="card-header">
            <strong>Tarifa base del itinerario</strong>
        </div>
        <div class="card-body">
            <p class="text-muted">
                Se usa cuando un tipo de pasajero no tiene precio propio.
                Total actual: <strong>{{ number_format($itinerary->fullPrice(), 2, ',', '.') }} €</strong> por persona.
                Las reservas ya hechas conservan el precio con el que se crearon.
            </p>
            @can('write-admin')
                <form action="{{ route('admin.itineraries.base-price.update', $itinerary) }}" method="POST" class="form-row align-items-end">
                    @csrf
                    @method('PUT')
                    <div class="form-group col-md-3">
                        <label for="base_price">Precio (€)</label>
                        <input type="number" name="price" id="base_price" step="0.01" min="0" required
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', $itinerary->price) }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="base_taxes">Tasas (€)</label>
                        <input type="number" name="taxes" id="base_taxes" step="0.01" min="0" required
                            class="form-control @error('taxes') is-invalid @enderror"
                            value="{{ old('taxes', $itinerary->taxes) }}">
                        @error('taxes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-info">Guardar tarifa base</button>
                    </div>
                </form>
            @else
                <p class="mb-0">
                    {{ number_format((float) $itinerary->price, 2, ',', '.') }} €
                    + {{ number_format((float) $itinerary->taxes, 2, ',', '.') }} € tasas.
                </p>
            @endcan
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('admin.itineraries.prices.update', $itinerary) }}" method="POST">
                @csrf
                @method('PUT')
                <fieldset @cannot('write-admin') disabled @endcannot>

                <p class="text-muted">
                    Deja <strong>precio y tasas vacíos</strong> para ese tipo de pasajero: se aplicará la tarifa base del itinerario.
                </p>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo de pasajero</th>
                                <th style="width: 200px">Precio (€)</th>
                                <th style="width: 200px">Tasas (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($passengerTypes as $type)
                                @php
                                    $row = $pricesByType->get($type->id);
                                @endphp
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $type->name }}</strong>
                                        <br><small class="text-muted">ID tipo {{ $type->id }}</small>
                                    </td>
                                    <td>
                                        <input type="number"
                                            name="prices[{{ $type->id }}][price]"
                                            class="form-control @error('prices.'.$type->id) is-invalid @enderror @error('prices.'.$type->id.'.price') is-invalid @enderror"
                                            step="0.01" min="0" placeholder="—"
                                            value="{{ old('prices.'.$type->id.'.price', $row?->price) }}">
                                    </td>
                                    <td>
                                        <input type="number"
                                            name="prices[{{ $type->id }}][taxes]"
                                            class="form-control @error('prices.'.$type->id) is-invalid @enderror @error('prices.'.$type->id.'.taxes') is-invalid @enderror"
                                            step="0.01" min="0" placeholder="—"
                                            value="{{ old('prices.'.$type->id.'.taxes', $row?->taxes) }}">
                                    </td>
                                </tr>
                                @error('prices.'.$type->id)
                                    <tr>
                                        <td colspan="3" class="border-0 pt-0">
                                            <div class="text-danger small">{{ $message }}</div>
                                        </td>
                                    </tr>
                                @enderror
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($passengerTypes->isEmpty())
                    <div class="alert alert-warning">
                        No hay tipos de pasajero en la tabla <code>types</code> con <code>typeable = Passenger</code>.
                        Ejecuta los seeders o crea esos tipos en el admin.
                    </div>
                @else
                    @can('write-admin')
                        <button type="submit" class="btn btn-primary">Guardar tarifas</button>
                    @endcan
                @endif
                </fieldset>
            </form>
        </div>
    </div>
@stop
