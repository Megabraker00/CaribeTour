@extends('front_template')
@section('title', 'Elige los mejores destinos del caribe - Especialistas en el Caribe')

@section('content')
<!-- container -->
    <div class="w-100 shadow-top"></div>
    <section class="container pt-4">

        <div class="row pt-4">

            @forelse($categories as $category)

            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="{{ route('destinos.pais', $category->slug) }}" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap2.png') }}" alt="Imagen de {{$category->name}}" title="{{$category->name}}" class="card-img">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title" title="{{$category->name}}"><i class="bi bi-geo-alt-fill"></i>{{$category->name}}</h5>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            @empty
                {{-- Esto se mostrará si NO hay tours --}}
                <div class="alert alert-info text-center shadow-sm">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Actualmente no hay paises disponibles en estos momentos. 
                    ¡Vuelve pronto o consulta otras provincias!
                </div>
                
                {{-- Opcional: Mostrar tours recomendados o un botón de volver --}}
                <div class="text-center">
                    <a href="{{ url('/') }}" class="btn btn-primary">Ver otros destinos</a>
                </div>
            @endforelse
            
        </div>

    </section>
    <div class="w-100 shadow-bottom"></div>
    <!-- /container -->
@endsection