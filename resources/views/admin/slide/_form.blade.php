@php
    $editing = $slide !== null;
    $pendingImage = session('slide_pending_image');
    $pendingImage = is_array($pendingImage) ? $pendingImage : null;
@endphp

<form action="{{ $editing ? route('admin.slides.update', $slide) : route('admin.slides.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="title">Título <span class="text-danger">*</span></label>
        <input type="text" name="title" id="title" maxlength="120"
            class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title', $slide?->title ?? '') }}" required autofocus>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="text">Texto</label>
        <input type="text" name="text" id="text" maxlength="200"
            class="form-control @error('text') is-invalid @enderror"
            value="{{ old('text', $slide?->text ?? '') }}" placeholder="Opcional. Una frase bajo el título.">
        @error('text')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="image">Imagen @unless ($editing)<span class="text-danger">*</span>@endunless</label>
        @if ($pendingImage)
            <div class="mb-2">
                <img src="{{ asset($pendingImage['path']) }}" alt="" style="width: 240px; aspect-ratio: 16 / 9; object-fit: cover;">
                <input type="hidden" name="kept_image" value="{{ $pendingImage['token'] }}">
                <div class="text-muted small">Imagen seleccionada. Si no eliges otra, se usará esta.</div>
            </div>
        @elseif ($editing)
            <div class="mb-2">
                <img src="{{ asset($slide->image_path) }}" alt="" style="width: 240px; aspect-ratio: 16 / 9; object-fit: cover;">
            </div>
        @endif
        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif,image/webp"
            class="form-control-file @error('image') is-invalid @enderror"
            @if (!$editing && !$pendingImage) required @endif
        @error('image')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">JPEG, PNG, GIF o WebP. Máximo 10 MB. Si no está en 16:9, se recorta por el centro sin deformarla. @if ($editing) Si no eliges otra, se conserva la actual.@endif</small>
    </div>

    <div class="form-group">
        <label for="product_id">Producto</label>
        <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror">
            <option value="">— Ninguno: usar el enlace —</option>
            @foreach ($products as $typeName => $group)
                <optgroup label="{{ $typeName }}">
                    @foreach ($group as $product)
                        <option value="{{ $product->id }}" @selected((string) old('product_id', $slide?->product_id ?? '') === (string) $product->id)>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        @error('product_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">Si eliges un producto, la foto enlaza a su ficha. Si deja de ser público, la diapositiva no se muestra.</small>
    </div>

    <div class="form-group">
        <label for="link_url">Enlace</label>
        <input type="text" name="link_url" id="link_url" maxlength="500"
            class="form-control @error('link_url') is-invalid @enderror"
            value="{{ old('link_url', $slide?->link_url ?? '') }}" placeholder="/contacto o https://…">
        @error('link_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">Para una oferta que no está en el catálogo. Si hay producto, se usa la ficha y este enlace queda en reserva.</small>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="sort_order">Orden <span class="text-danger">*</span></label>
            <input type="number" name="sort_order" id="sort_order" min="0" max="9999"
                class="form-control @error('sort_order') is-invalid @enderror"
                value="{{ old('sort_order', $slide?->sort_order ?? $nextOrder ?? 1) }}" required>
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-3">
            <label for="starts_on">Visible desde</label>
            <input type="date" name="starts_on" id="starts_on"
                class="form-control @error('starts_on') is-invalid @enderror"
                value="{{ old('starts_on', isset($slide) ? $slide->starts_on?->toDateString() : '') }}">
            @error('starts_on')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-3">
            <label for="ends_on">Visible hasta</label>
            <input type="date" name="ends_on" id="ends_on"
                class="form-control @error('ends_on') is-invalid @enderror"
                value="{{ old('ends_on', isset($slide) ? $slide->ends_on?->toDateString() : '') }}">
            @error('ends_on')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-3 d-flex align-items-end">
            <div class="custom-control custom-checkbox mb-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                    @checked(old('is_active', $slide?->is_active ?? true))>
                <label class="custom-control-label" for="is_active">Activa</label>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
