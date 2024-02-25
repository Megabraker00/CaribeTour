@extends('main_template')

@section('title', 'Unos destinos de puta madre')

@section('supheader')

    <div class="row subheader">
        <!-- tour search form -->
        <div class="threecol column subheader-block">
            @include('destination.search')
        </div>
        <!-- /tour search form -->

        <div class="ninecol column subheader-block last">
            <div class="main-slider-container content-slider-container">
                <div class="content-slider main-slider fade-effect" id="slider" style="position:relative">
                    <ul style="height: 370px;">

                        @foreach ($featured_products as $f_product)
                        <li>
                            <div class="featured-image">
                                <a hreflang="es" type="text/html" charset="UTF-8"
                                    href="destinos/{{ $f_product->category->fullSlug() }}">
                                    <div class="etiqueta-categoria" id="etiqueta">
                                        {{ $f_product->category->name }} desde <?php echo 'precio'; ?>&euro;
                                    </div><img width="824" height="370"
                                        src="{{ asset('images/' . $f_product->mainImage()->path) }}"
                                        class="attachment-large wp-post-image" alt="{{ $f_product->category->name }}"
                                        title="Ver Todos Los destinos de {{ $f_product->category->name }}" />
                                </a>
                            </div>
                        </li>
                        @endforeach

                    </ul>
                    <div class="arrow arrow-left content-slider-arrow"></div>
                    <div class="arrow arrow-right content-slider-arrow"></div>
                    <input type="hidden" class="slider-pause" value="7008">
                    <input type="hidden" class="slider-speed" value="400">
                    <input type="hidden" class="slider-effect" value="fade">
                    <div class="controls">
                        <a href="#" class="current"></a>
                        <a href="#" class=""></a>
                    </div>

                </div>

                <div class="block-background layer-1"></div>
                <div class="block-background layer-2"></div>

            </div>
        </div>
    </div>

@endsection


@section('site-content')

    <section class="container site-content">
        <div class="row">

            <div class="eightcol column">
                <div class="fivecol column">
                    <img alt="CaribeTour.es | Explora el mundo" class="alignnone size-medium wp-image-21 demo-image"
                        title="CaribeTour.es | Explora el mundo" src="images/explora.jpg">
                </div>
                <div class="sevencol column last">
                    <div class="section-title">
                        <h1>Explora el Mundo con CaribeTour.es</h1>
                    </div>
                    El Caribe es una región maravillosa que ofrece todos los atractivos necesarios: gente alegre y
                    hospitalaria que le recibirá con una sonrisa, playas vírgenes e interminables, bellas montañas repletas
                    de vegetación tropical, mares de colores únicos y fondos sorprendentes, cuevas ancestrales, amaneceres
                    sobrecogedores y una gastronomía sabrosa hasta para los paladares más exquisitos.!
                </div>
                <div class="clear"></div>
            </div>
            
            <div class="fourcol column last">
                <div class="content-slider testimonials-slider">
                    <ul style="height: 155px;">

                        <li>
                            <article class="testimonial">
                                <div class="quote-text">
                                    <div class="block-background">
                                        Tuvimos el m�s incre�ble viaje de luna de miel en Punta Cana gracias a ustedes. Sin
                                        dudas el viaje super� con creces nuestras expectativas. Gracias!
                                    </div>
                                </div>
                                <h6 class="quote-author">Maria Taveras</h6>
                            </article>
                        </li>

                        <li>
                            <article class="testimonial">
                                <div class="quote-text">
                                    <div class="block-background">
                                        Todo fue absolutamente incre�ble y todos los detalles eran simplemente perfecto. han
                                        hecho todo el viaje sin complicaciones! El mejor viaje que he tenido.
                                    </div>
                                </div>
                                <h6 class="quote-author">Juan Fornel Jimenez</h6>
                            </article>
                        </li>

                        <li>
                            <article class="testimonial">
                                <div class="quote-text">
                                    <div class="block-background">
                                        Thank you for the marvelous trip you arranged in the Dominican Republic. We could
                                        never have put together such a well-planned visit by ourselves. Amazing!
                                    </div>
                                </div>
                                <h6 class="quote-author">Lisa Blackwood</h6>
                            </article>
                        </li>
                    </ul>
                    <input type="hidden" class="slider-pause" value="0"><input type="hidden" class="slider-speed"
                        value="400">
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </section>


    <section class="container content-section">

        <div class="substrate section-substrate">
            <img src="images/background_1.jpg" class="fullwidth" alt="">
        </div>
        <div class="row">

            <div class="items-grid">

                @foreach ($tours as $index => $tour)

                <div class="column threecol @if(($index + 1) % 4 == 0) last @endif">
                    <div class="tour-thumb-container">
                        <div class="tour-thumb">
                            <a hreflang="es" type="text/html" charset="iso-8859-1"
                                href="{{ route('destinos') }}/{{ $tour->tourSlug() }}"><img
                                    width="440" height="330" src="{{ asset( 'images/' . $tour->mainImage()->path )}}"
                                    class="attachment-preview wp-post-image"
                                    alt="{{ $tour->name }}"
                                    title="{{ $tour->name }}"></a>
                            <div class="tour-caption">
                                <h5 class="tour-title"><a hreflang="es" type="text/html" charset="iso-8859-1"
                                        href="{{ route('destinos') }}/{{ $tour->tourSlug() }}"
                                        title="{{ $tour->name }} ">{{ $tour->name }}</a></h5>
                                <div class="tour-meta">
                                    <div class="tour-destination">
                                        <div class="colored-icon icon-2"></div>
                                        <a hreflang="es" type="text/html" charset="iso-8859-1"
                                            href="{{ route('destinos') }}/{{ $tour->category->fullSlug() }}" rel="tag"
                                            title="Ver todos los destinos de {{ $tour->category->name }}">{{ $tour->category->name }}</a>
                                    </div>
                                    <div class="colored-icon icon-3"></div>1.126,40€
                                </div>
                            </div>
                        </div>
                        <div class="block-background"></div>
                    </div>
                </div>

                @endforeach

                <div class="clear"></div>
            </div>
        </div>
    </section>


    <section class="container site-content">
        <div class="row">

            <div class="threecol column">
                <div class="section-title">
                    <a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}" title="Blogs de Viajes">
                        <h2>Blogs de Viajes</h2>
                    </a>
                </div>
                <div class="featured-blog">
                    <article
                        class="post-112 post type-post status-publish format-standard hentry category-guides tag-amet tag-dolor tag-lorem post">
                        <div class="featured-image">
                            <a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}/{{ $blog->slug }}"><img
                                    width="440" height="299" src="img/" class="attachment-normal wp-post-image"
                                    alt="Imagen de {{ ucwords($blog->name) }}" title="{{ ucwords($blog->name) }}"></a>
                        </div>
                        <div class="post-content">
                            <h5 class="post-title">
                                <a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}/{{ $blog->slug }}"
                                    title="{{ ucwords($blog->name) }}">{{ ucwords($blog->name) }}</a>
                            </h5>
                            <p>{{ substr($blog->content, 0, 121); }}.[...]</p>
                        </div>
                        <footer class="post-footer clearfix">
                            <a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}/{{ $blog->slug }}"
                                class="button small" title="Leer Más sobre {{ ucwords($blog->name) }}">Leer Más</a>
                            <div class="post-comment-count">1</div>
                            <div class="post-info">
                                <time datetime="{{ $blog->create_date() }}">{{ $blog->create_date() }}</time>
                            </div>
                        </footer>
                    </article>
                </div>
            </div>

            <div class="sixcol column">
                <div class="section-title">
                    <a hreflang="es" type="text/html" charset="iso-8859-1" href="galeria.php"
                        title="Ver la Galería Completa">
                        <h2>Excursiones</h2>
                    </a>
                </div>

                <div class="items-grid">

                    @foreach ($featured_excursions as $index => $f_excursion)

                    <div class="column gallery-item fourcol @if(($index + 1) % 3 == 0) last @endif">
                        <div class="featured-image">
                            <a href="{{ route('servicios') }}/{{ $f_excursion->tourSlug() }}">
                                <img width="440"
                                    height="330" src="img/vik-hotel-arena-blanca_14609041623.jpg"
                                    class="attachment-preview wp-post-image" alt="Imagen de {{ $f_excursion->name }}">
                            </a>
                            <a class="featured-image-caption hidden-caption" href="{{ route('servicios') }}/{{ $f_excursion->tourSlug() }}" style="bottom: -54px;">
                                <h6>{{ $f_excursion->name }}</h6>
                            </a>
                        </div>
                        <div class="block-background"></div>
                    </div>

                    @if(($index + 1) % 3 == 0) <div class="clear"></div> @endif

                    @endforeach
                    
                    <div class="clear"></div>
                </div>
            </div>

            <div class="threecol column last">
                <div class="widget widget-twitter">
                    <div class="section-title">
                        <h2>Últimos Tweets</h2>
                    </div>
                    <div id="tweets">
                        <div class="twitter-timeline twitter-timeline-rendered"
                            style="display: flex; width: 520px; max-width: 100%; margin-top: 0px; margin-bottom: 0px;">
                            <iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true"
                                allowfullscreen="true" class=""
                                style="position: static; visibility: visible; width: 251px; height: 366px; display: block; flex-grow: 1;"
                                title="Twitter Timeline"
                                src="https://syndication.twitter.com/srv/timeline-profile/screen-name/CaribeToures?dnt=false&amp;embedId=twitter-widget-0&amp;features=eyJ0ZndfdGltZWxpbmVfbGlzdCI6eyJidWNrZXQiOltdLCJ2ZXJzaW9uIjpudWxsfSwidGZ3X2ZvbGxvd2VyX2NvdW50X3N1bnNldCI6eyJidWNrZXQiOnRydWUsInZlcnNpb24iOm51bGx9LCJ0ZndfdHdlZXRfZWRpdF9iYWNrZW5kIjp7ImJ1Y2tldCI6Im9uIiwidmVyc2lvbiI6bnVsbH0sInRmd19yZWZzcmNfc2Vzc2lvbiI6eyJidWNrZXQiOiJvbiIsInZlcnNpb24iOm51bGx9LCJ0ZndfZm9zbnJfc29mdF9pbnRlcnZlbnRpb25zX2VuYWJsZWQiOnsiYnVja2V0Ijoib24iLCJ2ZXJzaW9uIjpudWxsfSwidGZ3X21peGVkX21lZGlhXzE1ODk3Ijp7ImJ1Y2tldCI6InRyZWF0bWVudCIsInZlcnNpb24iOm51bGx9LCJ0ZndfZXhwZXJpbWVudHNfY29va2llX2V4cGlyYXRpb24iOnsiYnVja2V0IjoxMjA5NjAwLCJ2ZXJzaW9uIjpudWxsfSwidGZ3X3Nob3dfYmlyZHdhdGNoX3Bpdm90c19lbmFibGVkIjp7ImJ1Y2tldCI6Im9uIiwidmVyc2lvbiI6bnVsbH0sInRmd19kdXBsaWNhdGVfc2NyaWJlc190b19zZXR0aW5ncyI6eyJidWNrZXQiOiJvbiIsInZlcnNpb24iOm51bGx9LCJ0ZndfdXNlX3Byb2ZpbGVfaW1hZ2Vfc2hhcGVfZW5hYmxlZCI6eyJidWNrZXQiOiJvbiIsInZlcnNpb24iOm51bGx9LCJ0ZndfdmlkZW9faGxzX2R5bmFtaWNfbWFuaWZlc3RzXzE1MDgyIjp7ImJ1Y2tldCI6InRydWVfYml0cmF0ZSIsInZlcnNpb24iOm51bGx9LCJ0ZndfbGVnYWN5X3RpbWVsaW5lX3N1bnNldCI6eyJidWNrZXQiOnRydWUsInZlcnNpb24iOm51bGx9LCJ0ZndfdHdlZXRfZWRpdF9mcm9udGVuZCI6eyJidWNrZXQiOiJvbiIsInZlcnNpb24iOm51bGx9fQ%3D%3D&amp;frame=false&amp;hideBorder=false&amp;hideFooter=false&amp;hideHeader=false&amp;hideScrollBar=false&amp;lang=es&amp;maxHeight=600px&amp;origin=http%3A%2F%2Fwww.caribetour.es%2F&amp;sessionId=c018a7125566c636592cdd3992665666388b35f2&amp;showHeader=true&amp;showReplies=false&amp;transparent=false&amp;widgetsVersion=2615f7e52b7e0%3A1702314776716"></iframe>
                        </div>
                        <script>
                            ! function(d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0],
                                    p = /^http:/.test(d.location) ? 'http' : 'https';
                                if (!d.getElementById(id)) {
                                    js = d.createElement(s);
                                    js.id = id;
                                    js.src = p + "://platform.twitter.com/widgets.js";
                                    fjs.parentNode.insertBefore(js, fjs);
                                }
                            }(document, "script", "twitter-wjs");
                        </script>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </section>

@endsection
