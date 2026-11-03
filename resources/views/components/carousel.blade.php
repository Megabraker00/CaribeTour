@if ($slides->isNotEmpty())
    <div class="col-md-12 col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-body">
                <div id="carouselExampleCaptions" class="carousel slide carousel-fade home-carousel" @if ($slides->count() > 1) data-bs-ride="carousel" @endif>
                    @if ($slides->count() > 1)
                        <div class="carousel-indicators">
                            @foreach ($slides as $slide)
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $loop->index }}" @if ($loop->first) class="active" aria-current="true" @endif aria-label="Diapositiva {{ $loop->iteration }}"></button>
                            @endforeach
                        </div>
                    @endif
                    <div class="carousel-inner">
                        @foreach ($slides as $slide)
                            @php
                                $href = $slide->href();
                                $external = str_starts_with((string) $href, 'http');
                            @endphp
                            <div class="carousel-item @if ($loop->first) active @endif">
                                <a href="{{ $href }}" class="home-slide-link" @if ($external) target="_blank" rel="noopener noreferrer" @endif>
                                    <img src="{{ asset($slide->image_path) }}" class="d-block w-100" alt="{{ $slide->title }}" @unless ($loop->first) loading="lazy" @endunless>
                                    <div class="carousel-caption">
                                        <h5>{{ $slide->title }}</h5>
                                        @if ($slide->text)
                                            <p class="mb-0">{{ $slide->text }}</p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if ($slides->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <i class="bi bi-chevron-left" aria-hidden="true"></i>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <i class="bi bi-chevron-right" aria-hidden="true"></i>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
