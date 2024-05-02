@extends('main_template')

@section('title', 'Unos destinos de puta madre')

@section('site-content')

    <div class="row">
        <div class="column ninecol">

            <div class="items-list clearfix">

                @foreach ($tours as $index => $tour)                    
                
                <div class="full-tour clearfix">                    
                    <div class="fivecol column">
                        <div class="content-slider-container tour-slider-container">
                            <div class="featured-image">
                                <a hreflang="es" type="text/html" charset="iso-8859-1"
                                    href="{{ route('destinos') }}/{{ $tour->tourSlug() }}"><img
                                        width="550" height="413" src="img/natura-park-beach-eco-resort-y-spa.jpg"
                                        class="attachment-extended wp-post-image"
                                        alt="{{ $tour->name }}"
                                        title="{{ $tour->name }}"></a>
                            </div>
                            <div class="block-background layer-2"></div>
                        </div>
                    </div>

                    <div class="sevencol column last">
                        <div class="section-title">
                            <h1><a hreflang="es" type="text/html" charset="iso-8859-1"
                                    href="{{ route('destinos') }}/{{ $tour->tourSlug() }}"
                                    title="{{ $tour->name }}">{{ $tour->name }}</a></h1>
                        </div>
                        <ul class="tour-meta">
                            <li>
                                <div class="colored-icon icon-2"></div><strong>destinos:</strong> <a hreflang="es"
                                    type="text/html" charset="iso-8859-1" href="{{ route('destinos') }}/{{ $tour->category->fullSlug() }}"
                                    title="Ver todos los destinos de Punta Cana" rel="tag">{{ $tour->category->name }}</a>
                            </li>
                            <li>
                                <div class="colored-icon icon-1"><span></span></div><strong>Duración:</strong> 8 Días
                            </li>
                            <li style="font-size:1.8em;">
                                <div class="colored-icon icon-3"><span></span></div><strong>Desde:</strong> 783,60€
                            </li>
                        </ul>
                        <p>En este resort se aprovechan los recursos naturales del área tales como las piedras, el coco, la
                            madera y la caña, creando un ambiente de extraordinaria belleza natural que parece estar
                            soñando...

                            viva la experiencia de unas vacaciones .[...]</p>
                        <footer class="tour-footer">
                            <a hreflang="es" type="text/html" charset="iso-8859-1"
                                href="{{ route('destinos') }}/{{ $tour->tourSlug() }}"
                                class="button small"
                                title="Saber más sobre {{ $tour->name }}"><span>Saber Más</span></a> <a
                                href="#question-form" data-id="{{ $tour->name }}"
                                data-title="{{ $tour->name }}"
                                class="button grey small colorbox inline cboxElement"
                                title="Hacer una consulta"><span>Consultar</span></a>
                        </footer>
                    </div>
                </div>

                @endforeach
                
            </div>

            <nav class="pagination">
                <span class="page-numbers current">1</span>
                @if (!empty($list))
                    {{ $tours->links() }}
                @endif
            </nav>

        </div>

        <aside class="column threecol last">

            <div class="widget widget_text">
                <div class="textwidget">

                    <!-- tour search form -->
                    @include('destination.search')
                    <!-- /tour search form -->

                </div>
            </div>

            <div class="widget widget_text">
                <div class="textwidget">
                    <div class="featured-image">
                        <a hreflang="es" type="text/html" charset="iso-8859-1" href="">
                            <img src="images/image_18.jpg" alt="">
                        </a>
                    </div>
                </div>
            </div>

        </aside>
        <!-- question form -->
        <div class="hidden">
            <div class="question-form popup-form" id="question-form">
                <div class="section-title popup-title">
                    <h4></h4>
                </div>
                <form action="mail.php" method="POST" class="formatted-form" name="clientes"
                    onsubmit="return validacion();">
                    <div class="sixcol column ">
                        <div class="field-container">
                            <input type="text" id="nombre" name="nombre" title="Introduzca su nombtre completo."
                                maxlength="50" value="" placeholder="Nombre Completo" required="">
                        </div>
                    </div>
                    <div class="sixcol column last">
                        <div class="clear"></div>
                        <div class="field-container">
                            <input type="email" id="email" name="email" title="Introduzca su email."
                                maxlength="80" value="" placeholder="Email" required="">
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="field-container">
                        <textarea id="mensaje" name="mensaje" title="Indique su consulta." maxlength="500" placeholder="Consultas"
                            required=""></textarea>
                    </div>
                    <input type="hidden" name="producto" id="producto" value=""
                        class="popup-id">
                    <input type="hidden" name="volver"
                        value="http://localhost/caribetour/destinos/republica-dominicana/punta-cana">
                    <input type="submit" value="Enviar" title="Enviar la consulta">
                    <!--<a class="submit-button button" href="#">Enviar</a> -->
                </form>
            </div>
            <!-- /question form -->
        </div>
    </div>

@endsection
