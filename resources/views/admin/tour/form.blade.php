@extends('admin.form_template')

@section('title', 'Tour')

@section('content_header')
    <h1>Tour</h1>
@stop

@section('content')

    <div class="row">

        <!-- col -->
        <div class="col-lg-6">
            <!-- card -->
            <div class="card">
                <!-- form -->
                <form novalidate
                    @if (isset($new)) method="POST" action="{{ route('admin.tour.store') }}" @endif
                    @if (isset($edit)) method="POST" action="{{ route('admin.tour.update', $tour->id) }}" @endif>
                    <div class="card-body">
                        @csrf
                        @if (isset($edit))
                            @method('PUT')
                        @endif

                        <fieldset {{ isset($show) ? 'disabled' : '' }}> {{-- disabled para deshabilitar todos los campos dentro del fieldset --}}
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input name="name" class="form-control @error('name') is-invalid @enderror" id="nombre"
                                    placeholder="Nombre" value="{{ old('name', $tour->name) }}" autofocus>
                                @error('name')
                                    <div class="error invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="form-gropu">
                                <label for="slug">Slug</label>
                                <div class="input-group">
                                    <input name="slug" id="slug"
                                        class="form-control @error('slug') is-invalid @enderror" placeholder="Slug"
                                        aria-label="Slug" aria-describedby="slug-from-name"
                                        pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$" value="{{ old('slug', $tour->slug) }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="slug-from-name"
                                            title="Generar el slug a partir del nombre del producto">Generar con el
                                            nombre</button>
                                    </div>
                                </div>
                                @error('slug')
                                    <div class="error invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    El slug debe contener solo letras minúsculas, números y guiones, sin espacios ni
                                    caracteres
                                    especiales. Ejemplo: mi-slug-amigable
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categoría</label>
                                <select name="category_id" id="categoria"
                                    class="form-control @error('category_id') is-invalid @enderror">
                                    <option> - </option>
                                    @foreach ($parentCategories as $parent)
                                        <optgroup label="{{ $parent }}">
                                            @foreach ($parent->subCategories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $tour->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="error invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="proveedor">Proveedor</label>
                                <select name="suplier_id" id="proveedor"
                                    class="form-control @error('suplier_id') is-invalid @enderror">
                                    <option> - </option>
                                    @foreach ($supliers as $suplier)
                                        <option value="{{ $suplier->id }}"
                                            {{ old('suplier_id', $tour->suplier_id) == $suplier->id ? 'selected' : '' }}>
                                            {{ $suplier }}</option>
                                    @endforeach
                                </select>
                                @error('suplier_id')
                                    <div class="error invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </fieldset>

                    </div>

                    @if (isset($show))
                        <div class="card-footer">
                            <a href="{{ route('admin.tour.edit', $tour->id) }}" class="btn btn-warning">Editar</a>
                        </div>
                    @else
                        <div class="card-footer">
                            <button type="submit" id="submit" class="btn btn-info">Guardar</button>
                        </div>
                    @endif

                </form>
                <!-- /form -->
            </div>
            <!-- /card -->
        </div>
        <!-- /col -->

        <div class="col-lg-6">
            @if (isset($show))
                <!-- card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Fechas
                        </div>
                    </div>

                    <form action="{{ route('admin.tour.date.store') }}" method="POST">

                        <div class="card-body">

                            @csrf
                            <div class="form-group">
                                <label for="fecha-salida">Fecha de Salida</label>
                                <input name="departure_date" id="fecha-salida" type="datetime-local"
                                    class="form-control @error('departure_date') is-invalid @enderror"
                                    value="{{ old('departure_date') }}">
                                @error('departure_date')
                                    <div class="error invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="terminal-salida">Terminal de Salida</label>
                                <select name="departure_terminal_id" type="select" id="terminal-salida"
                                    class="form-control @error('departure_terminal_id') is-invalid @enderror">
                                    <option value="">-</option>
                                    @foreach ($terminals as $terminal)
                                        <option value="{{ $terminal->id }}"
                                            {{ old('departure_terminal_id') == $terminal->id ? 'selected' : '' }}>
                                            {{ $terminal->name }}</option>
                                    @endforeach
                                </select>
                                @error('departure_terminal_id')
                                    <div class="error invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fecha-llegada">Fecha de Llegada</label>
                                <input name="arrival_date" id="fecha-llegada" type="datetime-local"
                                    class="form-control @error('arrival_date') is-invalid @enderror"
                                    value="{{ old('arrival_date') }}">
                                @error('arrival_date')
                                    <div class="error invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="terminal-llegada">Terminal de Llegada</label>
                                <select name="arrival_terminal_id" type="select" id="terminal-llegada"
                                    class="form-control @error('arrival_terminal_id') is-invalid @enderror">
                                    <option value="">-</option>
                                    @foreach ($terminals as $terminal)
                                        <option value="{{ $terminal->id }}"
                                            {{ old('arrival_terminal_id') == $terminal->id ? 'selected' : '' }}>
                                            {{ $terminal->name }}</option>
                                    @endforeach
                                </select>
                                @error('arrival_terminal_id')
                                    <div class="error invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="precio">Precio</label>
                                <input type="number" name="price" id="precio"
                                    class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}"
                                    step="0.01" min="0" placeholder="0.00">
                                @error('price')
                                    <div class="error invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="from-group">
                                <label for="tasas">Tasas</label>
                                <input type="number" name="taxes" id="tasas"
                                    class="form-control @error('taxes') is-invalid @enderror"
                                    value="{{ old('taxes') }}" step="0.01" min="0" placeholder="0.00">
                                @error('taxes')
                                    <div class="error invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" name="product_id" value="{{ $tour->id }}">
                        </div>

                        <div class="card-footer">
                            <button type="submit" id="submit" class="btn btn-info">Guardar</button>
                        </div>

                    </form>
                    <!-- /form -->
                </div>
                <!-- /card -->
            @endif

        </div>
        <!-- /col -->

    </div>
    <!-- /row -->


    <div class="row">
        <div class="col-12">

          @if (isset($show))
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Fechas de Salidas
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="the_table">
                            <caption>Lista de Tours</caption>
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Fecha de Salida</th>
                                    <th scope="col">Terminal de Salida</th>
                                    <th scope="col" nowrap>Fecha de Llegada</th>
                                    <th scope="col" nowrap>Terminal de Llegada</th>
                                    <th scope="col" nowrap>Precio</th>
                                    <th scope="col" nowrap>Tasas</th>
                                    <th nowrap></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Fecha de Salida</th>
                                    <th scope="col">Terminal de Salida</th>
                                    <th scope="col" nowrap>Fecha de Llegada</th>
                                    <th scope="col" nowrap>Terminal de Llegada</th>
                                    <th scope="col" nowrap>Precio</th>
                                    <th scope="col" nowrap>Tasas</th>
                                    <th nowrap></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /table-responsive -->
                </div>
                <!-- /card-body -->
            </div>
            <!-- /card -->
            @endif
        </div>
    </div>
    <!-- /row -->

    <script>
        function generateSlug(string, divider = "-") {
            // Convertir el string a minúsculas
            let slug = string.toLowerCase();

            // Reemplazar espacios y caracteres especiales con el divisor
            slug = slug.replace(/[^a-z0-9]+/g, divider);

            // Eliminar divisores repetidos consecutivos
            const regex = new RegExp(`\\${divider}{2,}`, 'g');
            slug = slug.replace(regex, divider);

            // Eliminar cualquier divisor al inicio o al final del string
            slug = slug.replace(new RegExp(`^\\${divider}+|\\${divider}+$`, 'g'), '');

            return slug;
        }

        window.addEventListener('load', () => {
            const generateSlugBtn = document.getElementById('slug-from-name');
            generateSlugBtn.addEventListener('click', (event) => {
                const nameField = document.getElementById('nombre')
                document.getElementById('slug').value = generateSlug(nameField.value)
                document.getElementById('categoria').focus();
            })
        });



        /*

        TODO: hacer una funcion que evite el reenvio de formulario (deshabilitar el boton submit despues de que sea clicado)

        $(function () {
          $.validator.setDefaults({
            submitHandler: function () {
              alert( "Form successful submitted!" );
            }
          });
          $('#quickForm').validate({
            rules: {
              email: {
                required: true,
                email: true,
              },
              password: {
                required: true,
                minlength: 5
              },
              terms: {
                required: true
              },
            },
            messages: {
              email: {
                required: "Please enter a email address",
                email: "Please enter a valid email address"
              },
              password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
              },
              terms: "Please accept our terms"
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
              error.addClass('invalid-feedback');
              element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
              $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
              $(element).removeClass('is-invalid');
            }
          });
        });
        */
    </script>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            let properties = dtProperties()
            properties.ajax = "{{ route('api.datatable.tours.dates', $tour->id) }}"
            properties.columns = [{
                    data: 'id'
                },
                {
                    data: 'departure_date',
                    render: (data) => {
                        // TODO: Pasar esta funcion a un js externo (util o helper)
                        // Parsear la fecha y la hora
                        const [datePart, timePart] = data.split(' ');

                        // Extraer el año, mes y día
                        const [year, month, day] = datePart.split('-');

                        // Formatear la nueva fecha
                        return `${day}-${month}-${year} ${timePart}`;
                    }
                },
                {
                    data: 'departure_t.name'
                },
                {
                    data: 'arrival_date'
                },
                {
                    data: 'arrival_t.name'
                },
                {
                    data: 'price',
                    render: (data) => `<span style="white-space: nowrap;">${data} &euro;</span>`
                },
                {
                    data: 'taxes',
                    render: (data) => `<span style="white-space: nowrap;">${data} &euro;</span>`
                },
                {
                    data: null,
                    render: (data, type, row) => {
                      let routePhp = "{{ route('admin.tour.date.destroy', 0) }}";
                      let newRoute = routePhp.replace('/0/', `/${row.id}/`);
                      return `<form method="POST" action="${newRoute}">
                        @csrf @method('DELETE')
                        <input type="submit" onclick="return confirm('¿Seguro de que deseas eliminar este registro?');" value="Eliminar" class="btn btn-sm btn-danger">
                        <input type="hidden" name="date_id" value="${row.id}">
                        <input type="hidden" name="product_id" value="{{ $tour->id }}">
                      </form>`
                    } 
                }
            ]

            $('#the_table').DataTable(properties)
        });
    </script>
@stop
