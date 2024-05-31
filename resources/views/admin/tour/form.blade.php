@extends('admin.form_template')

@section('title', 'Tour')

@section('content_header')
    <h1>Tour</h1>
@stop

@section('form')
    <!-- form -->
    <form novalidate
      @if (isset($new)) method="POST" action="{{ route('admin.tour.store') }}" @endif
      @if (isset($edit)) method="POST" action="{{ route('admin.tour.update', $tour->id) }}" @endif
     >
        <div class="card-body">
            @csrf
            @if (isset($edit)) @method('PUT') @endif

            <fieldset {{isset($show) ? 'disabled' : ''}}> {{-- disabled para deshabilitar todos los campos dentro del fieldset --}}
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
                        <input name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                            placeholder="Slug" aria-label="Slug" aria-describedby="slug-from-name"
                            pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$" value="{{ old('slug', $tour->slug) }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="slug-from-name"
                                title="Generar el slug a partir del nombre del producto">Generar con el nombre</button>
                        </div>
                    </div>
                    @error('slug')
                        <div class="error invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        El slug debe contener solo letras minúsculas, números y guiones, sin espacios ni caracteres
                        especiales. Ejemplo: mi-slug-amigable
                    </small>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoría</label>
                    <select name="category_id" id="categoria" class="form-control @error('category_id') is-invalid @enderror">
                        <option> - </option>                        
                        @foreach ($parentCategories as $parent)
                        <optgroup label="{{$parent}}">
                            @foreach ($parent->subCategories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $tour->category_id) == $category->id ? 'selected' : '' }}>{{ $category }}</option>
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
                    <select name="suplier_id" id="proveedor" class="form-control @error('suplier_id') is-invalid @enderror">
                        <option> - </option>
                        @foreach ($supliers as $suplier)
                        <option value="{{ $suplier->id }}" {{ old('suplier_id', $tour->suplier_id) == $suplier->id ? 'selected' : '' }}>{{ $suplier }}</option>
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
            <a href="{{route('admin.tour.edit', $tour->id)}}" class="btn btn-warning">Editar</a>
        </div>
        @else
        <div class="card-footer">
            <button type="submit" id="submit" class="btn btn-info">Guardar</button>
        </div>
        @endif
        
    </form>
    <!-- /form -->
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
