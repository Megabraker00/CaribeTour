@extends('front_template')
@section('title', 'Elige los mejores destinos del caribe - Especialistas en el Caribe')

@section('content')
<!-- container -->
    <div class="w-100 shadow-top"></div>
    <section class="container pt-4">

        <div class="row pt-4">

            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="pais.html" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="img/tour/i+Love+Bootstrap (4).png" alt="Imagen del hotel Grand Palladium Punta Cana Resort & Spa" title="Grand Palladium Punta Cana Resort & Spa" class="card-img">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title" title="Hotel Grand Palladium Punta Cana Resort & Spa"><i class="bi bi-geo-alt-fill"></i> República. Dominicana</h5>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="/" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="img/tour/i+Love+Bootstrap (1).png" class="card-img">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title"><i class="bi bi-geo-alt-fill"></i> Mexico</h5>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="/" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="img/tour/i+Love+Bootstrap (3).png" class="card-img">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title"><i class="bi bi-geo-alt-fill"></i> Cuba</h5>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </section>
    <div class="w-100 shadow-bottom"></div>
    <!-- /container -->
@endsection