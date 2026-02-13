@extends('front_template')

@section('title', 'desde el index CaribeTour.es - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))

@section('hero')
    <div class="row pt-4">
        @include('components.carousel')
        
        <div class="col-md-12 col-lg-4">
            @include('components.buscador')
        </div>
    </div>
@endsection

@section('content')
<!-- testimonial -->
    <div class="bottom-shadow">
        <section class="container py-4">
            <div class="row">
                <div class="col-md-4 mb-4" title="Explora el Mundo con CaribeTour" style="background-image: url('{{ asset('images/explora.jpg') }}'); background-repeat: no-repeat; background-size: contain; background-position: center;"></div>

                <div class="col-md-12 col-lg-8">
                    <h3>Explora el mundo con CaribeTour</h3>
                    <hr>

                    <div class="row">

                        <div class="col-md-6">
                            <p>
                                ¡Bienvenidos a Caribe Tour! Nos complace recibirte en nuestra plataforma, donde cada viaje comienza con un sueño y se convierte en una experiencia inolvidable. En Caribe Tour, estamos comprometidos a hacer que tu próximo viaje sea más que un simple destino. 
                            </p>
            
                        </div>
            
                        <div class="col-md-6">
                            <p>
                                En Caribe Tour, nos enorgullece nuestra reputación de excelencia en el servicio, la atención al cliente y en la oferta de los mejores precios del mercado. Gracias a nuestras alianzas estratégicas con los principales proveedores de viajes, te garantizamos tarifas competitivas y promociones exclusivas que no encontrarás en ningún otro lugar. 
                            </p>
            
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>
    <!-- /testimonial -->

    <!-- tour-list -->
    <section class="bg-palm py-4">
        <div class="container">
            <div class="row pt-4">

                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <a href="{{ route('destinos.tour', ['country' => 'republica-dominicana', 'province' => 'punta-cana', 'tour' => 'hotel-carolina']) }}" class="text-decoration-none">
                        <div class="card shadow zoom">
                            <div class="card-body">
                                <img src="{{ asset('images/site_bg.jpg') }}" alt="Imagen del hotel Grand Palladium Punta Cana Resort & Spa" title="Grand Palladium Punta Cana Resort & Spa" class="card-img">
                            </div>
                            <div class="card-footer">
                                <h5 class="card-title" title="Hotel Grand Palladium Punta Cana Resort & Spa">Grand Palladium Punta Cana Resort & Spa <span class="star-5 fs-6"></span></h5>
                                <div class="card-subtitle mb-2 text-muted" title="Ubicado en Punta Cana - República Dominicana"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                                <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                            </div>
                        </div>
                    </a>
                </div>
    
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <a href="{{ route('destinos.tour', ['country' => 'republica-dominicana', 'province' => 'punta-cana', 'tour' => 'hotel-carolina']) }}" class="text-decoration-none">
                        <div class="card shadow zoom">
                            <div class="card-body">
                                <div class="image-placeholder">
                                    <img src="{{ asset('images/image_8.jpg') }}"
                                        class="card-img"
                                        onerror="this.parentNode.classList.add('img-error')">
                                </div>
                            </div>
                            <div class="card-footer">
                                <h5 class="card-title">Ocean Blue & Sand Resort <span class="star-5 fs-6"></span></h5>
                                <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Santo Domingo - Rep. Dominicana</small></div>
                                <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                            </div>
                        </div>
                    </a>
                </div>
    
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <a href="{{ route('destinos.tour', ['country' => 'republica-dominicana', 'province' => 'punta-cana', 'tour' => 'hotel-ocean-blue-sand-resort']) }}" class="text-decoration-none">
                        <div class="card shadow zoom">
                            <div class="card-body">
                                <img src="{{ asset('images/image_9.jpg') }}" class="card-img">
                            </div>
                            <div class="card-footer">
                                <h5 class="card-title">Vista Sol Punta Cana Beach Resort & Spa <span class="star-4 fs-6"></span></h5>
                                <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                                <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <a href="{{ route('destinos.tour', ['country' => 'republica-dominicana', 'province' => 'punta-cana', 'tour' => 'hotel-ocean-el-faro-resort']) }}" class="text-decoration-none">
                        <div class="card shadow zoom">
                            <div class="card-body">
                                <img src="{{ asset('images/image_10.jpg') }}" class="card-img">
                            </div>
                            <div class="card-footer">
                                <h5 class="card-title">Hotel Ocean el Faro Resort <span class="star-5 fs-6"></span></h5>
                                <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                                <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <a href="{{ route('destinos.tour', ['country' => 'republica-dominicana', 'province' => 'punta-cana', 'tour' => 'hotel-vista-sol-punta-cana-beach-resort-spa']) }}" class="text-decoration-none">
                        <div class="card shadow zoom">
                            <div class="card-body">
                                <img src="{{ asset('images/image_11.jpg') }}" class="card-img">
                            </div>
                            <div class="card-footer">
                                <h5 class="card-title">Vista Sol Punta Cana Beach Resort & Spa <span class="star-4 fs-6"></span></h5>
                                <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                                <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <a href="{{ route('destinos.tour', ['country' => 'republica-dominicana', 'province' => 'punta-cana', 'tour' => 'hotel-royal-sun-resort']) }}" class="text-decoration-none">
                        <div class="card shadow zoom">
                            <div class="card-body">
                                <img src="{{ asset('images/image_12.jpg') }}" class="card-img">
                            </div>
                            <div class="card-footer">
                                <h5 class="card-title">Vista Sol Punta Cana Beach Resort & Spa <span class="star-4 fs-6"></span></h5>
                                <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                                <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <a href="{{ route('destinos.tour', ['country' => 'republica-dominicana', 'province' => 'punta-cana', 'tour' => 'hotel-carolina']) }}" class="text-decoration-none">
                        <div class="card shadow zoom">
                            <div class="card-body">
                                <img src="{{ asset('images/image_8.jpg') }}" class="card-img">
                            </div>
                            <div class="card-footer">
                                <h5 class="card-title">Vista Sol Punta Cana Beach Resort & Spa <span class="star-4 fs-6"></span></h5>
                                <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                                <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <a href="{{ route('destinos.tour', ['country' => 'republica-dominicana', 'province' => 'punta-cana', 'tour' => 'hotel-catalonia-bavaro-beach-golf-casino-lujo']) }}" class="text-decoration-none">
                        <div class="card shadow zoom">
                            <div class="card-body">
                                <img src="{{ asset('images/image_14.jpg') }}" class="card-img">
                            </div>
                            <div class="card-footer">
                                <h5 class="card-title">Catalonia Bávaro Beach, Golf & Casino Lujo <span class="star-5 fs-6"></span></h5>
                                <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                                <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                            </div>
                        </div>
                    </a>
                </div>
    
            </div>
        </div>
    </section>
    <!-- /tour-list -->

    <!-- blog-excursions-rrss -->
    <div class="top-shadow">
        <section class="container py-4">
            <div class="row mb-4 ">

                <div class="col-md-3 mb-4">
                    <a href="blogs" class="text-decoration-none" title="Ver Todos Los Blogs">
                        <h4 class="border-bottom mb-4">Blog de Viajes</h4>
                    </a>
                    <div class="card">
                        <img src="{{ asset('images/image_15.jpg') }}" class="card-img" alt="Cómo viajar sin miedo">
                        <div class="card-body">
                            <h5 class="card-title">Cómo viajar sin miedo </h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                                the card's content...</p>
                        </div>
                        <div class="card-footer">
                            <h6 class="card-subtitle mb-2 text-muted">Categoría</h6>
                            <a class="btn btn-primary stretched-link" href="#">Saber más</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <a href="excursiones" class="text-decoration-none" title="Ver Todos Las Excursiones">
                        <h4 class="border-bottom mb-4">Excursiones</h4>
                    </a>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                            <div class="card shadow zoom-out">
                                <img src="{{ asset('images/image_16.jpg') }}"
                                    class="card-img mb-2" alt="Excursiones">
                                <div class="card-body">

                                    <h6 class="card-subtitle mb-2 text-muted">Excusrsion al Páramo</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                            <div class="card shadow  zoom-out">
                                    <img src="{{ asset('images/image_17.jpg') }}"
                                        class="card-img mb-2" alt="Excursiones"
                                        onerror="this.classList.add('img-error')">
                                <div class="card-body">

                                    <h6 class="card-subtitle mb-2 text-muted">Galopando por el Caribe</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                            <div class="card shadow  zoom-out">
                                <img src="{{ asset('images/image_14.jpg') }}"
                                    class="card-img mb-2" alt="Tocando el Cielo">
                                <div class="card-body">

                                    <h6 class="card-subtitle mb-2 text-muted">Tocando el Cielo</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                            <div class="card shadow zoom-out">
                                <img src="{{ asset('images/image_9.jpg') }}"
                                    class="card-img mb-2" alt="Excursion al Páramo">
                                <div class="card-body">

                                    <h6 class="card-subtitle mb-2 text-muted">Excursion al Páramo</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                            <div class="card shadow zoom-out">
                                <img src="{{ asset('images/image_10.jpg') }}"
                                    class="card-img mb-2" alt="Galopando por el Caribe">
                                <div class="card-body">

                                    <h6 class="card-subtitle mb-2 text-muted">Galopando por el Caribe</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                            <a href="#" class="text-decoration-none">
                            <div class="card shadow zoom-out">
                                <img src="{{ asset('images/image_11.jpg') }}"
                                    class="card-img mb-2" alt="Tocando el Cielo">
                                <div class="card-body">

                                    <h6 class="card-subtitle mb-2 text-muted">Tocando el Cielo</h6>
                                </div>
                            </div>
                            </a>
                        </div>

                    </div>

                </div>

                <div class="col-md-3 mb-4">
                    <h4 class="border-bottom mb-4">Twitter o Instagram</h4>

                    <!-- <iframe src="https://www.instagram.com/p/C3FgE1Hti0o/"></iframe> -->

                    <div id="tweets">
                        <div class="twitter-timeline twitter-timeline-rendered"
                            style="display: flex; width: 520px; max-width: 100%; margin-top: 0px; margin-bottom: 0px;">
                            <!-- <iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true"
                                allowfullscreen="true" class=""
                                style="position: static; visibility: visible; width: 251px; height: 366px; display: block; flex-grow: 1;"
                                title="Twitter Timeline"
                                src="https://syndication.twitter.com/srv/timeline-profile/screen-name/CaribeToures?dnt=false&amp;embedId=twitter-widget-0&amp;features=eyJ0ZndfdGltZWxpbmVfbGlzdCI6eyJidWNrZXQiOltdLCJ2ZXJzaW9uIjpudWxsfSwidGZ3X2ZvbGxvd2VyX2NvdW50X3N1bnNldCI6eyJidWNrZXQiOnRydWUsInZlcnNpb24iOm51bGx9LCJ0ZndfdHdlZXRfZWRpdF9iYWNrZW5kIjp7ImJ1Y2tldCI6Im9uIiwidmVyc2lvbiI6bnVsbH0sInRmd19yZWZzcmNfc2Vzc2lvbiI6eyJidWNrZXQiOiJvbiIsInZlcnNpb24iOm51bGx9LCJ0ZndfZm9zbnJfc29mdF9pbnRlcnZlbnRpb25zX2VuYWJsZWQiOnsiYnVja2V0Ijoib24iLCJ2ZXJzaW9uIjpudWxsfSwidGZ3X21peGVkX21lZGlhXzE1ODk3Ijp7ImJ1Y2tldCI6InRyZWF0bWVudCIsInZlcnNpb24iOm51bGx9LCJ0ZndfZXhwZXJpbWVudHNfY29va2llX2V4cGlyYXRpb24iOnsiYnVja2V0IjoxMjA5NjAwLCJ2ZXJzaW9uIjpudWxsfSwidGZ3X3Nob3dfYmlyZHdhdGNoX3Bpdm90c19lbmFibGVkIjp7ImJ1Y2tldCI6Im9uIiwidmVyc2lvbiI6bnVsbH0sInRmd19kdXBsaWNhdGVfc2NyaWJlc190b19zZXR0aW5ncyI6eyJidWNrZXQiOiJvbiIsInZlcnNpb24iOm51bGx9LCJ0ZndfdXNlX3Byb2ZpbGVfaW1hZ2Vfc2hhcGVfZW5hYmxlZCI6eyJidWNrZXQiOiJvbiIsInZlcnNpb24iOm51bGx9LCJ0ZndfdmlkZW9faGxzX2R5bmFtaWNfbWFuaWZlc3RzXzE1MDgyIjp7ImJ1Y2tldCI6InRydWVfYml0cmF0ZSIsInZlcnNpb24iOm51bGx9LCJ0ZndfbGVnYWN5X3RpbWVsaW5lX3N1bnNldCI6eyJidWNrZXQiOnRydWUsInZlcnNpb24iOm51bGx9LCJ0ZndfdHdlZXRfZWRpdF9mcm9udGVuZCI6eyJidWNrZXQiOiJvbiIsInZlcnNpb24iOm51bGx9fQ%3D%3D&amp;frame=false&amp;hideBorder=false&amp;hideFooter=false&amp;hideHeader=false&amp;hideScrollBar=false&amp;lang=es&amp;maxHeight=600px&amp;origin=http%3A%2F%2Fwww.caribetour.es%2F&amp;sessionId=c018a7125566c636592cdd3992665666388b35f2&amp;showHeader=true&amp;showReplies=false&amp;transparent=false&amp;widgetsVersion=2615f7e52b7e0%3A1702314776716"></iframe> -->
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </div>
    <!-- /blog-excursions-rrss -->

@endsection