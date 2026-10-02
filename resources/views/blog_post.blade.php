@extends('front_template')

@section('title', 'desde el index CaribeTour.es - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))


@section('content')
    <section class="container my-4">

        <div class="row">

            <div class="col-md-8">

                <article>
                    <img src="{{ asset($post->mainImage()?->path ?? 'images/i-love-bootstrap2.png') }}" class="img-thumbnail mb-4" alt="{{ $post->mainImageAlt() }}" width="822">
                    <h2>{{ $post->name }}</h2>
                    <hr>
                    {!! $post->content !!}

                    <p>
                        <strong>Publicado:</strong> 
                        <time datetime="19-09-2024">19-09-2024</time>
                    </p>
                            
                    @include('components.blog-author', ['blog' => $post])

                    <div title="Etiquetas" class="mb-4">
                        <span class="badge bg-primary">Primary</span>
                        <span class="badge bg-secondary">Secondary</span>
                        <span class="badge bg-success">Success</span>
                        <span class="badge bg-danger">Danger</span>
                        <span class="badge bg-warning text-dark">Warning</span>
                        <span class="badge bg-info text-dark">Info</span>
                        <span class="badge bg-dark">Dark</span>
                    </div>
                    
                </article>


                <div id="rrss text-center" class="fs-4">
                    <h5>Compartir en:</h5>
                    <i class="bi bi-facebook" style="color: rgb(6, 82, 223);" title="Facebook"></i>
                    <i class="bi bi-instagram" style="color: rgb(233, 62, 147);" title="Instagram"></i>
                    <i class="bi bi-linkedin" style="color: #0a66c2;" title="Linkedin"></i>
                    <i class="bi bi-twitter" style="color: #45a3fe;" title="Twitter"></i>
                    <i class="bi bi-twitter-x" style="color: black;" title="X"></i>
                    <i class="bi bi-telegram" style="color: #45a3fe;" title="Telegram"></i>
                    <i class="bi bi-whatsapp" style="color: rgb(50, 126, 50);" title="Whatsapp"></i>
                    <i class="bi bi-envelope-at-fill text-secondary"  title="Enviar por email"></i>
                    <i class="bi bi-share-fill" title="Copiar enlace"></i>

                </div>

                <hr>

                
                <form method="post" class="mb-4">
                    <fieldset>
                        <legend>Deja tu comentario</legend>
                        <div class="row">

                            <div class="mb-3 col">
                                
                                <label class="form-label"><strong>Email</strong></label>
                                <input class="form-control" type="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="mb-3 col">
                                
                                <label class="form-label"><strong>Nick Name</strong></label>
                                <input class="form-control" name="nick-name" placeholder="Nick Name" required>
                            </div>
                        </div>
                        <div class="mb-3">

                            <label class="form-label"><strong>Comentario</strong></label>
                            <textarea rows="7" class="form-control" name="comentario" placeholder="Tu Comentario"></textarea>
                        </div>

                    </fieldset>
                    <input type="submit" class="btn btn-primary" value="Comentar">
                </form>

                <h4>
                    <i class="bi bi-chat-right-text-fill"></i> Comentarios 
                    <span class="badge rounded-pill bg-secondary ms-2">75</span>
                </h4>
                <hr>

                <div id="comentarios">
                    <div class="card mb-4">
                        <div class="card-body">

                            <h5 class="card-title">
                                Megrabraker 
                            </h5>
                            <p class="card-text">
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Possimus eaque unde molestias culpa? Tempore excepturi odio adipisci dicta molestias explicabo, debitis eveniet reprehenderit nulla nemo inventore quae sequi. Ipsum, voluptates!
                            </p>
                            <div>
                                <i class="bi bi-hand-thumbs-up-fill" title="Me Gusta"></i>
                                <i class="bi bi-hand-thumbs-down-fill" title="No me gusta"></i>
                                <time datetime="19-09-2024">19-09-2024</time>
                            </div>

                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">

                            <h5 class="card-title">
                                Megrabraker 
                            </h5>
                            <p class="card-text">
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Possimus eaque unde molestias culpa? Tempore excepturi odio adipisci dicta molestias explicabo, debitis eveniet reprehenderit nulla nemo inventore quae sequi. Ipsum, voluptates!
                            </p>
                            <div>
                                <i class="bi bi-hand-thumbs-up-fill"></i>
                                <i class="bi bi-hand-thumbs-down-fill"></i>
                                <time datetime="19-09-2024">19-09-2024</time>
                            </div>

                        </div>
                    </div>

                </div>


            </div>

            <!-- post populares, port relacionados, galería, publicidad -->
            <div class="col-md-4">
                @include('components.promo')
            </div>
        </div>


    </section>
@endsection