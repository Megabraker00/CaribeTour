@props(['products'])

@if ($products->isNotEmpty())
    <div class="row my-4">
        <h2 class="h4">Productos relacionados</h2>
        <hr>

        @foreach ($products as $related)
            @php
                $relatedImage = $related->images->first(fn ($image) => (bool) $image->is_main) ?? $related->images->first();
                $relatedPrice = $related->cheapestItinerary();
                $relatedUrl = $related->type?->slug === \App\Models\Type::TOUR
                    ? route('destinos.tour', [
                        'country' => $related->category?->parentCategory?->slug,
                        'province' => $related->category?->slug,
                        'tour' => $related->slug,
                    ])
                    : route('servicios.detalle', $related->slug);
            @endphp
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="{{ $relatedUrl }}" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img loading="lazy" src="{{ asset($relatedImage?->path ?? 'images/image_12.jpg') }}" alt="Imagen del {{ $related->name }}" title="{{ $related->name }}" class="img-aspect-4-3 card-img img-fluid" decoding="async">
                        </div>
                        <div class="card-footer">
                            <h3 class="h5 card-title" title="{{ $related->name }}">{{ $related->name }}</h3>
                            <ul class="tour-info">
                                <li title="{{ $related->starsLabel() }}">
                                    @if ($related->stars() > 0)
                                        <span class="star-{{ $related->stars() }} fs-6" role="img" aria-label="{{ $related->starsLabel() }}"></span>
                                    @else
                                        <span class="text-muted small">Sin clasificar</span>
                                    @endif
                                </li>
                                <li class="text-muted" title="Destino: {{ $related->category }} - {{ $related->category?->parentCategory }}">
                                    <i class="bi bi-geo-alt-fill" aria-hidden="true"></i><small>{{ $related->category }}</small>
                                </li>
                                @if ($relatedPrice)
                                    <li title="Precio por persona">
                                        <i class="bi bi-tag-fill" aria-hidden="true"></i>Desde <span class="price" title="Precio desde {{ $relatedPrice->fullPrice() }}&euro;">{{ $relatedPrice->fullPrice() }}&euro;</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endif
