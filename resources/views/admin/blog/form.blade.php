@php
    $blog = $blog ?? null;
    $isEdit = $blog !== null;
    $blogMainImage = $isEdit ? $blog->mainImage() : null;
@endphp
<form action="{{ $action }}" method="POST" enctype="multipart/form-data" data-disable-on-submit>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <div class="form-group">
        <label for="name">Título <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" maxlength="255"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $blog?->name) }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="slug">Slug <span class="text-danger">*</span></label>
        <input type="text" name="slug" id="slug" maxlength="255"
            class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', $blog?->slug) }}" required
            pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$">
        @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">Solo minúsculas, números y guiones. Si lo dejas vacío al guardar, se genera del título.</small>
    </div>
    <div class="form-group">
        <label for="status_id">Estado <span class="text-danger">*</span></label>
        <select name="status_id" id="status_id" class="form-control @error('status_id') is-invalid @enderror" required>
            <option value="">— Selecciona —</option>
            @foreach ($statuses as $st)
                <option value="{{ $st->id }}" {{ (string) old('status_id', $blog?->status_id) === (string) $st->id ? 'selected' : '' }}>
                    {{ $st->name }}
                </option>
            @endforeach
        </select>
        @error('status_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="content">Contenido <span class="text-danger">*</span></label>
        <div class="pell-blog-content-wrapper @error('content') is-invalid @enderror">
            <div id="pell-blog-content-mount" class="pell"></div>
            <textarea name="content" id="content" class="d-none" rows="12"
                autocomplete="off">{{ old('content', $blog?->content) }}</textarea>
        </div>
        @error('content')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">HTML; editor <strong>Pell</strong>, igual que la descripción del tour.</small>
    </div>
    @if ($blogMainImage)
        <div class="form-group">
            <label>Imagen principal actual</label>
            <div class="border rounded p-2 bg-light" style="max-width: 320px;">
                <img src="{{ asset($blogMainImage->path) }}" alt="{{ $blog->mainImageAlt() }}" class="img-fluid rounded">
            </div>
            <small class="form-text text-muted">Sube un archivo nuevo para reemplazarla.</small>
        </div>
    @endif
    <div class="form-group">
        <label for="blog_image">{{ $blogMainImage ? 'Nueva imagen principal' : 'Imagen principal' }}</label>
        <div class="custom-file">
            <input type="file" name="blog_image" id="blog_image"
                class="custom-file-input @error('blog_image') is-invalid @enderror"
                accept="image/jpeg,image/png,image/gif,image/webp">
            <label class="custom-file-label" for="blog_image">Elegir archivo…</label>
        </div>
        @error('blog_image')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">JPEG, PNG, GIF o WebP. Máx. 10 MB.</small>
    </div>
    <div class="form-group">
        <label for="blog_image_alt">Texto alternativo (alt)</label>
        <input type="text" name="blog_image_alt" id="blog_image_alt" maxlength="255"
            class="form-control @error('blog_image_alt') is-invalid @enderror"
            value="{{ old('blog_image_alt', $blogMainImage?->alt) }}"
            placeholder="Describe la imagen para accesibilidad y SEO">
        @error('blog_image_alt')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">Se usa en el atributo <code>alt</code> de la imagen principal. Si lo dejas vacío, se usa el título del post.</small>
    </div>
    <hr>
    <h5 class="mb-3">Redes sociales del autor</h5>
    <p class="text-muted small">Opcional. Si hay URL, se muestra el icono junto al nombre del autor en el blog.</p>
    <div class="form-group">
        <label for="author_linkedin">LinkedIn</label>
        <input type="url" name="author_linkedin" id="author_linkedin" maxlength="255"
            class="form-control @error('author_linkedin') is-invalid @enderror"
            value="{{ old('author_linkedin', $blog?->author_linkedin) }}"
            placeholder="https://www.linkedin.com/in/usuario">
        @error('author_linkedin')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="author_facebook">Facebook</label>
        <input type="url" name="author_facebook" id="author_facebook" maxlength="255"
            class="form-control @error('author_facebook') is-invalid @enderror"
            value="{{ old('author_facebook', $blog?->author_facebook) }}"
            placeholder="https://www.facebook.com/usuario">
        @error('author_facebook')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="author_instagram">Instagram</label>
        <input type="url" name="author_instagram" id="author_instagram" maxlength="255"
            class="form-control @error('author_instagram') is-invalid @enderror"
            value="{{ old('author_instagram', $blog?->author_instagram) }}"
            placeholder="https://www.instagram.com/usuario">
        @error('author_instagram')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="author_x">X</label>
        <input type="url" name="author_x" id="author_x" maxlength="255"
            class="form-control @error('author_x') is-invalid @enderror"
            value="{{ old('author_x', $blog?->author_x) }}"
            placeholder="https://x.com/usuario">
        @error('author_x')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Actualizar' : 'Guardar' }}</button>
</form>
@include('admin.partials.disable-on-submit')
<script>
    (function () {
        const nameField = document.getElementById('name');
        const slugField = document.getElementById('slug');
        if (!nameField || !slugField) {
            return;
        }
        nameField.addEventListener('blur', function () {
            if (slugField.value.trim() !== '') {
                return;
            }
            slugField.value = nameField.value
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });
    })();
    document.getElementById('blog_image')?.addEventListener('change', function () {
        var label = this.nextElementSibling;
        if (label && label.classList.contains('custom-file-label')) {
            label.textContent = (this.files && this.files[0]) ? this.files[0].name : 'Elegir archivo…';
        }
    });
</script>
