@extends('front_template')
@section('title', 'Elige los mejores destinos del caribe - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))

@section('content')
    <!-- container -->
    <section class="container py-4">

        <div class="row">

            <div class="row g-3" id="galleryGrid">
                @foreach($images as $img)
                <div class="col-6 col-md-4 col-lg-3">
                    <a class="img-fluid" href="{{ asset($img->path) }}?250" data-toggle="lightbox"
                        data-gallery="gallery"
                        data-caption="{{ $img->name }}, Subida el {{ $img->created_at->format('d-m-Y') }}"
                        data-size="lg"
                        data-type="image">
                        <img src="{{ asset($img->path) }}?250"
                            class="img-fluid img-thumbnail rounded shadow-sm zoom"                        
                            alt="">
                    </a>
                </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $images->links('pagination::bootstrap-5') }}
            </div>

        </div>



    </section>
    <!-- /container -->

    <!-- Lightbox JS https://trvswgnr.github.io/bs5-lightbox/#image-gallery -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.5/dist/index.bundle.min.js"></script>

@endsection