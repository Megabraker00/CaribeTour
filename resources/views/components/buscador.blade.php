<!-- buscador -->

    <div class="card shadow mb-4 tour-search-form">

        <div class="card-header form-title">
            <h5 class="text-center">Encuentra tu Destino</h5>
        </div>

        <form action="{{ route('resultados') }}" method="get">
            <div class="card-body">

                <div class="mb-3">
                    <label for="origen" class="form-label">Origen</label>
                    <select name="origen" id="origen" class="form-select">
                        <option value="">Cualquier origen</option>
                        @foreach ($searchOrigins['loose'] as $terminal)
                            <option value="{{ $terminal->id }}" @selected((string) request('origen') === (string) $terminal->id)>{{ $terminal->name }}</option>
                        @endforeach
                        @foreach ($searchOrigins['groups'] as $parentName => $terminals)
                            <optgroup label="{{ $parentName }}">
                                @foreach ($terminals as $terminal)
                                    <option value="{{ $terminal->id }}" @selected((string) request('origen') === (string) $terminal->id)>{{ $terminal->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="destino" class="form-label">Destino</label>
                    <select name="destino" id="destino" class="form-select">
                        <option value="">Todos los destinos</option>
                        @foreach ($searchProvinces as $countryName => $provinces)
                            <optgroup label="{{ $countryName }}">
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->parentCategory->slug }}/{{ $province->slug }}" @selected(request('destino') === $province->parentCategory->slug.'/'.$province->slug)>{{ $province->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="fecha-salida" class="form-label">Fecha de Salida</label>
                    <input name="salida" type="date" class="form-control" id="fecha-salida"
                        min="{{ now()->toDateString() }}"
                        value="{{ $searchDate }}"
                        placeholder="Fecha de Salida" />
                </div>

            </div>
            <div class="card-footer text-center">
                <div class="col-12">
                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </div>

            </div>
        </form>
    </div>

<!-- /buscador -->
