@extends('front_template')

@section('title', 'desde el index CaribeTour.es - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))


@section('content')
    <section class="container py-4">

        <div class="row">
            <div class="col-md-8">
                @foreach ($blogs as $index => $blog ) 
                <article>
                    <div class="card mb-4">
                        <div class="card-body">
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="text-decoration-none">
                                <img src="{{ asset('images/i-love-bootstrap2.png') }}" width="100%" class="mb-4" alt="{{ $blog->name }}">
                            </a>

                            <a href="{{ route('blogs.show', $blog->slug) }}" class="text-decoration-none">
                                <h4 class="card-title">
                                    {{ $blog->name }}
                                </h4>
                            </a>

                            <p class="card-text">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit blanditiis ipsam, quasi aut veritatis neque perferendis enim odit sint tempora eos iure at expedita sit itaque hic! Aut, quis eum.
                            </p>

                            <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis voluptatem consequatur exercitationem enim expedita non architecto eaque amet eligendi alias saepe illum animi, suscipit quo dolorem, ipsa odit. Inventore, fu...</p>
                            <p><strong>Publicado:</strong> <time datetime="19-09-2024">19-09-2024</time></p>
                            
                            <div class="mb-4">
                                <a href="https://www.linkedin.com/feed/"  title="Autor" target="_blank" class="text-decoration-none">
                                    <img src="img/autores/autor2.JPG" width="10%" class="img-thumbnail rounded-circle" alt="...">
                                    <span class="fs-6">
                                        <strong>Emilio Gutierrez </strong> <i class="bi bi-linkedin text-primary"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="card-footer">
                            <i class="bi bi-chat-right-text-fill"></i> Comentarios 
                            <span class="badge rounded-pill bg-secondary ms-2">75</span>
                            <i class="bi bi-eye-fill"></i>3.654

                            <div title="Etiquetas">
                                <span class="badge bg-primary">Primary</span>
                                <span class="badge bg-secondary">Secondary</span>
                                <span class="badge bg-success">Success</span>
                                <span class="badge bg-danger">Danger</span>
                                <span class="badge bg-warning text-dark">Warning</span>
                                <span class="badge bg-info text-dark">Info</span>
                                <span class="badge bg-dark">Dark</span>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach

                
                <article>
                    <div class="card mb-4">
                        <div class="card-body">
                            <a href="{{ route('blogs.show', 'ultimo-post') }}" class="text-decoration-none">
                            <img src="{{ asset('images/i-love-bootstrap1.png') }}" width="100%" class="mb-4" alt="...">
                            </a>

                            <a href="{{ route('blogs.show', 'ultimo-post') }}" class="text-decoration-none">
                            <h4 class="card-title">
                                Este es el título del blog
                            </h4>
                            </a>

                            <p class="card-text">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit blanditiis ipsam, quasi aut veritatis neque perferendis enim odit sint tempora eos iure at expedita sit itaque hic! Aut, quis eum.
                            </p>

                            <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis voluptatem consequatur exercitationem enim expedita non architecto eaque amet eligendi alias saepe illum animi, suscipit quo dolorem, ipsa odit. Inventore, fugiat.</p>
                            
                            <div title="Autor">
                                <!-- <i class="bi bi-person-badge-fill"></i> -->
                                <img src="img/autores/autor2.JPG" width="10%" class="img-thumbnail rounded-circle" alt="...">
                                Emilio Gutierrez 
                                Publicado <time datetime="19-09-2024">19/09/2024</time>
                            </div>
                        </div>
                        <div class="card-footer">
                            <i class="bi bi-chat-right-text-fill"></i> <span class="badge rounded-pill bg-light text-dark">75</span> Comentarios

                            <div title="Etiquetas">
                                <span class="badge bg-primary">Primary</span>
                                <span class="badge bg-secondary">Secondary</span>
                                <span class="badge bg-success">Success</span>
                                <span class="badge bg-danger">Danger</span>
                                <span class="badge bg-warning text-dark">Warning</span>
                                <span class="badge bg-info text-dark">Info</span>
                                <span class="badge bg-dark">Dark</span>
                            </div>
                        </div>
                    </div>
                </article>

                
                <article>
                    <div class="card mb-4">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap3.png') }}" width="100%" class="mb-4" alt="...">

                            <h4 class="card-title">
                                Este es el título del blog
                            </h4>

                            <p class="card-text">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit blanditiis ipsam, quasi aut veritatis neque perferendis enim odit sint tempora eos iure at expedita sit itaque hic! Aut, quis eum.
                            </p>

                            <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis voluptatem consequatur exercitationem enim expedita non architecto eaque amet eligendi alias saepe illum animi, suscipit quo dolorem, ipsa odit. Inventore, fugiat.</p>
                            
                            <div title="Autor">
                                <!-- <i class="bi bi-person-badge-fill"></i> -->
                                <img src="{{ asset('images/teleoperadora.jpg') }}" width="10%" class="img-thumbnail rounded-circle" alt="...">
                                Emilio Gutierrez <br/>
                                <time datetime="19-09-2024">19/09/2024</time>
                            </div>
                        </div>
                        <div class="card-footer">
                            <i class="bi bi-chat-right-text-fill"></i> <span class="badge rounded-pill bg-light text-dark">75</span> Comentarios

                            <div title="Etiquetas">
                                <span class="badge bg-primary">Primary</span>
                                <span class="badge bg-secondary">Secondary</span>
                                <span class="badge bg-success">Success</span>
                                <span class="badge bg-danger">Danger</span>
                                <span class="badge bg-warning text-dark">Warning</span>
                                <span class="badge bg-info text-dark">Info</span>
                                <span class="badge bg-dark">Dark</span>
                            </div>
                        </div>
                    </div>
                </article>


            </div>

            <div class="col-md-4">
                <div class="row">
                    <img src="{{ asset('images/publi.jpg') }}" width="100%" class="mb-4" alt="...">
                </div>
                <div class="row">
                    <img src="{{ asset('images/publi.jpg') }}" width="100%" class="mb-4" alt="...">
                </div>
            </div>
        </div>

    </section>
@endsection