@extends('front_template')
@section('title', $category->name)
@section('description', $metaDescription)
@section('og_image', $ogImage ?? asset('images/og-default.jpg'))

@section('content')
    <!-- container -->
    <section class="container my-4">

        @php
            $paisHeroImg = $category->images->firstWhere('is_main', true) ?? $category->images->first();
            $paisHeroSrc = $paisHeroImg?->path ?? 'images/i-love-bootstrap2.png';
        @endphp
        <!-- Destination -->
        <div class="row">

            <div class="col">
                <div class="card mb-3">
                <div class="row g-0">
                  <div class="col-md-6">
                    <img src="{{ asset($paisHeroSrc) }}" class="img-fluid rounded-start" alt="{{ $category->name }}">
                  </div>
                  <div class="col-md-6">
                    <div class="card-body">
                      <h1 class="h3 card-title">{{$category->name}}</h1>
                      @if (!empty($category->meta['description']))
                          <div class="card-text tour-description">{!! $category->meta['description'] !!}</div>
                      @else
                          <p class="card-text text-muted">Descubre las provincias y tours disponibles en este destino.</p>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>

            

        </div>
        <!-- /Destination -->

        <div class="row mt-4">

            @forelse ($subCategories as $subCategory)
                @php
                    $provImg = $subCategory->images->firstWhere('is_main', true) ?? $subCategory->images->first();
                    $provImgSrc = $provImg?->path ?? 'images/i-love-bootstrap4.png';
                @endphp
                <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                    <a href="{{ route('destinos.provincia', ['country' => $category->slug, 'province' => $subCategory->slug]) }}" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset($provImgSrc) }}" alt="Imagen de {{$subCategory->name}}" title="{{$subCategory->name}}" class="card-img" loading="lazy" decoding="async">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title" title="{{$subCategory->name}}">{{$subCategory->name}}</h5>
                            <div class="card-subtitle mb-2 text-muted" title="Ubicado en {{$subCategory->name}} - {{$category->name}}"><i class="bi bi-geo-alt-fill"></i> <small> {{$subCategory->name}} - {{$category->name}}</small></div>
                            @if (isset($prices[$subCategory->id]))
                                <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde {{ $prices[$subCategory->id] }}&euro;">{{ $prices[$subCategory->id] }}&euro;</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @empty
                <x-other-destinations>
                    Actualmente no hay provincias disponibles en <strong>{{ $category->name }}</strong>.
                    ¡Vuelve pronto o consulta otras provincias!
                </x-other-destinations>
            @endforelse           

        </div>

    </section>
    <!-- /container -->
@endsection