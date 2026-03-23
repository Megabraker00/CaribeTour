@extends('adminlte::page')

@section('title', 'Nueva categoría')

@section('css')
    <style>
        #pell-category-meta-description-mount.pell {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            background: #fff;
        }
        #pell-category-meta-description-mount .pell-content {
            min-height: 220px;
            outline: none;
        }
        .pell-category-meta-description-wrapper.is-invalid #pell-category-meta-description-mount.pell {
            border-color: #dc3545;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pell@1.0.6/dist/pell.min.css" crossorigin="anonymous">
@endsection

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Nueva categoría</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" maxlength="100"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="slug">Slug <span class="text-danger">*</span></label>
                    <input type="text" name="slug" id="slug" maxlength="150"
                        class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug') }}" required
                        pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$"
                        placeholder="ej.: republica-dominicana">
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Único dentro del mismo nivel (mismo padre). Solo minúsculas, números y guiones.</small>
                </div>
                <div class="form-group">
                    <label for="parent_id">Categoría padre</label>
                    <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                        <option value="">— Ninguna (categoría principal) —</option>
                        @foreach ($parentOptions as $p)
                            <option value="{{ $p->id }}" {{ (string) old('parent_id') === (string) $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->slug }})
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status_id">Estado <span class="text-danger">*</span></label>
                    <select name="status_id" id="status_id" class="form-control @error('status_id') is-invalid @enderror" required>
                        <option value="">— Selecciona —</option>
                        @foreach ($statuses as $st)
                            <option value="{{ $st->id }}" {{ (string) old('status_id') === (string) $st->id ? 'selected' : '' }}>
                                {{ $st->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Imagen y descripción</h5>

                <div class="form-group">
                    <label for="category_image">Imagen de la categoría</label>
                    <div class="custom-file">
                        <input type="file" name="category_image" id="category_image"
                            class="custom-file-input @error('category_image') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/gif,image/webp">
                        <label class="custom-file-label" for="category_image">Elegir archivo…</label>
                    </div>
                    @error('category_image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">JPEG, PNG, GIF o WebP. Máx. 10 MB.</small>
                </div>

                <div class="form-group">
                    <label for="category_meta_description">Descripción</label>
                    <div class="pell-category-meta-description-wrapper @error('meta_description') is-invalid @enderror">
                        <div id="pell-category-meta-description-mount" class="pell"></div>
                        <textarea name="meta_description" id="category_meta_description"
                            class="form-control d-none @error('meta_description') is-invalid @enderror"
                            rows="6" placeholder="Texto descriptivo…">{{ old('meta_description') }}</textarea>
                    </div>
                    @error('meta_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Se guarda en <code>meta_data</code> (HTML; editor <strong>Pell</strong>).</small>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/pell@1.0.6/dist/pell.min.js" crossorigin="anonymous"></script>
    <script>
        (function initPellCategoryDescription() {
            var mount = document.getElementById('pell-category-meta-description-mount');
            var ta = document.getElementById('category_meta_description');
            if (!mount || !ta || typeof window.pell === 'undefined' || typeof window.pell.init !== 'function') {
                return;
            }
            var editor = window.pell.init({
                element: mount,
                onChange: function (html) {
                    ta.value = html;
                },
                defaultParagraphSeparator: 'p',
                actions: [
                    'bold',
                    'italic',
                    'underline',
                    'heading2',
                    'paragraph',
                    'quote',
                    'line',
                    'olist',
                    'ulist',
                    'link',
                ],
            });
            editor.content.innerHTML = ta.value || '';
            var form = ta.closest('form');
            if (form) {
                form.addEventListener('submit', function () {
                    ta.value = editor.content.innerHTML;
                });
            }
        })();

        document.getElementById('category_image')?.addEventListener('change', function () {
            var label = this.nextElementSibling;
            if (label && label.classList.contains('custom-file-label')) {
                label.textContent = (this.files && this.files[0]) ? this.files[0].name : 'Elegir archivo…';
            }
        });
    </script>
@endsection
