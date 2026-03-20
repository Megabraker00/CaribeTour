@extends('admin.form_template')

@section('title', 'Tour')

@section('css')
    @parent
    <style>
        /* Misma lógica que el frontend (tarjetas 4:3): caja con relación fija + imagen sin recorte ni distorsión */
        .admin-tour-media-box {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .admin-tour-media-box img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        .admin-tour-image-card .card-footer {
            background: #fff;
        }
        .admin-tour-carousel-inner .carousel-item {
            background: #f0f0f0;
        }
        .admin-tour-thumb-trigger {
            cursor: pointer;
            transition: box-shadow 0.2s ease;
        }
        .admin-tour-thumb-trigger:hover {
            box-shadow: inset 0 0 0 3px rgba(0, 123, 255, 0.35);
        }
        .admin-tour-thumb-trigger.admin-tour-thumb-active {
            box-shadow: inset 0 0 0 3px #007bff;
        }
    </style>
@endsection

@section('content_header')
    <h1>Tour</h1>
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
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tour-info-tab" data-toggle="pill" href="#tour-info"
                                role="tab" aria-controls="tour-info" aria-selected="true">Información</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tour-images-tab" data-toggle="pill" href="#tour-images"
                                role="tab" aria-controls="tour-images" aria-selected="false">Imágenes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tour-itineraries-tab" data-toggle="pill" href="#tour-itineraries"
                                role="tab" aria-controls="tour-itineraries" aria-selected="false">Itinerarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill"
                                href="#custom-tabs-four-settings" role="tab"
                                aria-controls="custom-tabs-four-settings" aria-selected="false">Settings</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="tour-info" role="tabpanel"
                            aria-labelledby="tour-info-tab">

                            <!-- form -->
                            <form novalidate
                                @if (isset($new)) method="POST" action="{{ route('admin.tour.store') }}" @endif
                                @if (isset($edit)) method="POST" action="{{ route('admin.tour.update', $tour->id) }}" @endif>

                                @csrf
                                @if (isset($edit))
                                    @method('PUT')
                                @endif

                                <fieldset {{ isset($show) ? 'disabled' : '' }} class="col-md-6"> {{-- disabled para deshabilitar todos los campos dentro del fieldset --}}
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input name="name" class="form-control @error('name') is-invalid @enderror"
                                            id="nombre" placeholder="Nombre" value="{{ old('name', $tour->name) }}"
                                            autofocus>
                                        @error('name')
                                            <div class="error invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                    <div class="form-gropu">
                                        <label for="slug">Slug</label>
                                        <div class="input-group">
                                            <input name="slug" id="slug"
                                                class="form-control @error('slug') is-invalid @enderror"
                                                placeholder="Slug" aria-label="Slug" aria-describedby="slug-from-name"
                                                pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$"
                                                value="{{ old('slug', $tour->slug) }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="slug-from-name"
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
                                        <select name="supplier_id" id="proveedor"
                                            class="form-control @error('supplier_id') is-invalid @enderror">
                                            <option> - </option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ old('supplier_id', $tour->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier }}</option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <div class="error invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </fieldset>

                                <fieldset {{ isset($show) ? 'disabled' : '' }} class="col-12 mt-3">
                                    <legend class="text-secondary border-bottom w-100 pb-2">Contenido público (meta_data)</legend>
                                    <p class="text-muted small">Se muestra en la ficha del tour (<code>destination/tour</code>): descripción e ítems &quot;Incluye&quot;.</p>

                                    <div class="form-group">
                                        <label for="meta_description">Descripción del tour</label>
                                        <textarea name="meta_description" id="meta_description" rows="6"
                                            class="form-control @error('meta_description') is-invalid @enderror"
                                            placeholder="Texto descriptivo del viaje…">{{ old('meta_description', $tour->meta['description'] ?? '') }}</textarea>
                                        @error('meta_description')
                                            <div class="error invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Equivale a <code>$tour-&gt;meta['description']</code></small>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label for="meta_includes">Incluye (lista del itinerario / servicios)</label>
                                        <textarea name="meta_includes" id="meta_includes" rows="10"
                                            class="form-control @error('meta_includes') is-invalid @enderror"
                                            placeholder="Una línea por cada elemento de la lista.&#10;Ej.: Vuelos de ida y vuelta&#10;Traslados aeropuerto - hotel">{{ old('meta_includes', isset($tour->meta['includes']) && is_array($tour->meta['includes']) ? implode("\n", $tour->meta['includes']) : '') }}</textarea>
                                        @error('meta_includes')
                                            <div class="error invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Cada línea será un <code>&lt;li&gt;</code> en la web. Equivale a <code>$tour-&gt;meta['includes']</code></small>
                                    </div>
                                </fieldset>


                                @if (isset($show))
                                        <a href="{{ route('admin.tour.edit', $tour->id) }}"
                                            class="btn btn-warning">Editar</a>
                                @else
                                        <button type="submit" id="submit" class="btn btn-info">Guardar</button>
                                @endif

                            </form>
                            <!-- /form -->

                        </div>


                        <div class="tab-pane fade" id="tour-images" role="tabpanel" aria-labelledby="tour-images-tab">
                            @if (! $tour->id)
                                <p class="text-muted">Guarda el tour primero (pestaña Información) para poder subir imágenes.
                                    Se guardarán en <code>public/images/{{ '{slug-del-tour}' }}/</code> con el nombre
                                    <code>slug-milisegundos.ext</code> (JPEG, PNG, GIF, WebP).</p>
                            @else
                                <div class="col-12 mb-4">
                                    <form action="{{ route('admin.tour.images.store', $tour->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="tour_images_input">Seleccionar imágenes</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="images[]" class="custom-file-input"
                                                        id="tour_images_input" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp"
                                                        multiple required>
                                                    <label class="custom-file-label" for="tour_images_input">Elegir
                                                        archivos…</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-upload"></i> Subir
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">Formatos: JPG, PNG, GIF, WebP. Máx. 10 MB
                                                por archivo. Hasta 30 archivos por envío.</small>
                                            <p class="form-text text-muted mb-0 mt-2">
                                                <i class="fas fa-star text-warning"></i> La <strong>imagen principal</strong> es la que se muestra en listados y reservas; puedes cambiarla con el botón en cada miniatura.
                                            </p>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-12">
                                    @if ($tour->images->isEmpty())
                                        <p class="text-muted">Aún no hay imágenes para este tour.</p>
                                    @else
                                        <p class="text-muted small mb-2">Vista previa (misma ruta que usa el frontend:
                                            <code>asset('…')</code>). Edita los títulos en las tarjetas y pulsa <strong>Guardar nombres de todas las imágenes</strong> una sola vez.</p>

                                        {{-- Formulario vacío: los inputs usan form="tour-images-names-form" para no anidar <form> con principal/eliminar --}}
                                        <form action="{{ route('admin.tour.images.names', $tour->id) }}" method="POST"
                                            id="tour-images-names-form" class="d-none" aria-hidden="true">
                                            @csrf
                                        </form>

                                        <div id="adminTourImagesCarousel" class="carousel slide" data-ride="carousel"
                                            data-interval="false">
                                            <ol class="carousel-indicators">
                                                @foreach ($tour->images as $img)
                                                    <li data-target="#adminTourImagesCarousel"
                                                        data-slide-to="{{ $loop->index }}"
                                                        class="{{ $img->is_main ? 'active' : '' }}"></li>
                                                @endforeach
                                            </ol>
                                            <div class="carousel-inner rounded border admin-tour-carousel-inner">
                                                @foreach ($tour->images as $img)
                                                    <div class="carousel-item {{ $img->is_main ? 'active' : '' }}">
                                                        <div class="admin-tour-media-box rounded-top">
                                                            <img src="{{ asset($img->path) }}" alt="{{ $img->name }}">
                                                        </div>
                                                        <div class="carousel-caption d-none d-md-block bg-dark"
                                                            style="opacity: .85;">
                                                            <strong>{{ $img->name }}</strong>
                                                            <br><small>{{ $img->path }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <a class="carousel-control-prev" href="#adminTourImagesCarousel"
                                                role="button" data-slide="prev">
                                                <span class="carousel-control-custom-icon" aria-hidden="true">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                                <span class="sr-only">Anterior</span>
                                            </a>
                                            <a class="carousel-control-next" href="#adminTourImagesCarousel"
                                                role="button" data-slide="next">
                                                <span class="carousel-control-custom-icon" aria-hidden="true">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                                <span class="sr-only">Siguiente</span>
                                            </a>
                                        </div>

                                        @error('image_names')
                                            <div class="alert alert-danger small mt-2 mb-0">{{ $message }}</div>
                                        @enderror

                                        <div class="row mt-3">
                                            @foreach ($tour->images as $img)
                                                <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                                                    <div class="card h-100 shadow-sm admin-tour-image-card">
                                                        <div class="card-body p-0">
                                                            <div class="admin-tour-media-box admin-tour-thumb-trigger rounded-top {{ $img->is_main ? 'admin-tour-thumb-active' : '' }}"
                                                                role="button"
                                                                tabindex="0"
                                                                data-carousel-index="{{ $loop->index }}"
                                                                title="Ver esta imagen en el carrusel (clic)">
                                                                <img src="{{ asset($img->path) }}"
                                                                    alt="{{ $img->name }}"
                                                                    loading="lazy">
                                                            </div>
                                                        </div>
                                                        <div class="card-footer p-2 border-top-0 pt-2">
                                                            <label class="small text-muted d-block mb-0" for="img-name-{{ $img->id }}">Título / zona</label>
                                                            <input type="text" name="image_names[{{ $img->id }}]" id="img-name-{{ $img->id }}"
                                                                form="tour-images-names-form"
                                                                class="form-control form-control-sm mb-2 @error('image_names.'.$img->id) is-invalid @enderror"
                                                                value="{{ old('image_names.'.$img->id, $img->name) }}"
                                                                maxlength="255"
                                                                placeholder="Ej.: Piscina, habitación deluxe…"
                                                                title="Texto que verá el usuario en la galería (no cambia el archivo)">
                                                            @error('image_names.'.$img->id)
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                            @if ($img->is_main)
                                                                <span class="badge badge-success mb-1 d-inline-block"><i class="fas fa-star"></i> Principal</span>
                                                            @else
                                                                <form action="{{ route('admin.tour.images.main', [$tour->id, $img->id]) }}"
                                                                    method="POST" class="mb-1">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-outline-primary btn-block"
                                                                        title="Usar esta imagen en listados y página de reserva">
                                                                        <i class="far fa-star"></i> Usar como principal
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            <form
                                                                action="{{ route('admin.tour.images.destroy', [$tour->id, $img->id]) }}"
                                                                method="POST" class="d-inline mt-1"
                                                                onsubmit="return confirm('¿Eliminar esta imagen del disco y de la base de datos?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                    <i class="fas fa-trash"></i> Eliminar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-2 mb-3">
                                            <button type="submit" class="btn btn-secondary" form="tour-images-names-form">
                                                <i class="fas fa-save"></i> Guardar nombres de todas las imágenes
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>


                        <div class="tab-pane fade" id="tour-itineraries" role="tabpanel"
                            aria-labelledby="tour-itineraries-tab">

                            <form action="{{ route('admin.tour.date.store') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <fieldset class="col-md-6">
                                        <legend>Salidas</legend>

                                        <div class="form-group">
                                            <label for="fecha-salida">Fecha y Hora de Salida</label>
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
                                            <label for="precio">Precio</label>
                                            <input type="number" name="price" id="precio" class="form-control @error('price') is-invalid @enderror" step="0.01" min="0" placeholder="0.00" value="{{ old('price') }}">
                                            @error('price')
                                                <div class="error invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </fieldset>

                                    <fieldset class="col-md-6">
                                        <legend>Llegadas</legend>

                                        <div class="form-group">
                                            <label for="fecha-llegada">Fecha y Hora de Llegada</label>
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

                                        <div class="form-group">
                                            <label for="tasas">Tasas</label>
                                            <input type="number" name="taxes" id="tasas"
                                                class="form-control @error('taxes') is-invalid @enderror"
                                                value="{{ old('taxes') }}" step="0.01" min="0" placeholder="0.00">
                                            @error('taxes')
                                                <div class="error invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>


                                    </fieldset>

                                </div>
                                <input type="submit" value="Aceptar" class="btn btn-info">
                                <input type="hidden" name="product_id" value="{{ $tour->id }}">
                            </form>


                            <hr class="my-4">

                            <div class="row">
                                <div class="col-12">

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="the_table" style="width: 100%">
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

                            </div>
                            

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel"
                            aria-labelledby="custom-tabs-four-settings-tab">
                            Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis
                            ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate.
                            Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec
                            interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at
                            consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst.
                            Praesent imperdiet accumsan ex sit amet facilisis.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

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
            $(document).on('change', '#tour_images_input', function () {
                var label = $(this).next('.custom-file-label');
                if (this.files && this.files.length > 1) {
                    label.html(this.files.length + ' archivos seleccionados');
                } else if (this.files && this.files.length === 1) {
                    label.html(this.files[0].name);
                } else {
                    label.html('Elegir archivos…');
                }
            });

            @if (! $tour->id)
                return;
            @endif
            let properties = dtProperties()
            properties.ajax = "{{ route('api.datatable.tours.itineraries', $tour->id) }}"
            properties.columns = [{
                    data: 'id'
                },
                {
                    data: 'departure_date',
                    render: (data) => {
                        if (!data || typeof data !== 'string') return '—';
                        const [datePart, timePart] = data.split(' ');
                        if (!datePart) return '—';
                        const [year, month, day] = datePart.split('-');
                        if (!year || !month || !day) return data;
                        return `${day}-${month}-${year}${timePart ? ' ' + timePart : ''}`;
                    }
                },
                {
                    data: 'departure_t.name'
                },
                {
                    data: 'arrival_date',
                    render: (data) => {
                        if (!data || typeof data !== 'string') return '—';
                        const [datePart, timePart] = data.split(' ');
                        if (!datePart) return '—';
                        const [year, month, day] = datePart.split('-');
                        if (!year || !month || !day) return data;
                        return `${day}-${month}-${year}${timePart ? ' ' + timePart : ''}`;
                    }
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
                      </form>`
                    }
                }
            ]

            $('#the_table').DataTable(properties)

            var $adminCarousel = $('#adminTourImagesCarousel')
            if ($adminCarousel.length) {
                $adminCarousel.carousel({ interval: false })

                function syncAdminTourThumbHighlight() {
                    var idx = $adminCarousel.find('.carousel-item.active').index()
                    $('.admin-tour-thumb-trigger').removeClass('admin-tour-thumb-active')
                    $('.admin-tour-thumb-trigger[data-carousel-index="' + idx + '"]').addClass('admin-tour-thumb-active')
                }

                $(document).on('click', '.admin-tour-thumb-trigger', function () {
                    var idx = $(this).data('carousel-index')
                    $adminCarousel.carousel(idx)
                    var el = document.getElementById('adminTourImagesCarousel')
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'nearest' })
                    }
                })

                $(document).on('keydown', '.admin-tour-thumb-trigger', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault()
                        $(this).trigger('click')
                    }
                })

                $adminCarousel.on('slid.bs.carousel', syncAdminTourThumbHighlight)
            }
        });
    </script>
@stop
