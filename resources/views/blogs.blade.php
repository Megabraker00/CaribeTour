@extends('main_template')

@section('title', 'Contacta con nosotros')

@section('site-content')

    <div class="row">
        <div class="column eightcol">

            @foreach ($blogs as $index => $blog )                
            
            <div class="blog-listing">
                <article class="post-112 post type-post status-publish format-standard hentry category-guides post clearfix">
                    <div class="column fivecol post-featured-image">
                        <div class="featured-image">
                            <a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}/{{ $blog->slug }}"><img
                                    width="440" height="299" src="img/" alt="{{ $blog->name }}"
                                    title="{{ $blog->name }}"></a>
                        </div>
                    </div>
                    <div class="column sevencol last">
                        <div class="post-content">
                            <div class="section-title">
                                <h1><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}/{{ $blog->slug }}"
                                        title="{{ $blog->name }}">{{ $blog->name }}</a></h1>
                            </div>
                            <p>esto es la descripción del blog.[...]</p>
                            <footer class="post-footer clearfix">
                                <a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}/{{ $blog->slug }}"
                                    class="button small"><span>Leer Más</span></a>
                                <div class="post-comment-count">1</div>
                                <div class="post-info"><time datetime="2012-11-24">10-02-2024</time></div>
                            </footer>
                        </div>
                    </div>
                </article>
            </div>

            @endforeach


            <nav class="pagination">
                <span class="page-numbers current">1</span>
            </nav>
        </div>
        <!--/column eightcol-->
        <aside class="column fourcol last">
            <div class="widget widget-selected-posts">
                <div class="section-title">
                    <h4>Artículos Populares</h4>
                </div>
                <article class="post clearfix">
                    <div class="post-featured-image">
                        <div class="featured-image">
                            <a hreflang="es" type="text/html" charset="iso-8859-1" href="blog/un-blog-de-prueba"><img
                                    width="440" height="299" src="img/" class="attachment-normal wp-post-image"
                                    alt="Un Blog De Prueba" title="Un Blog De Prueba"></a>
                        </div>
                    </div>
                    <div class="post-content">
                        <h6 class="post-title"><a hreflang="es" type="text/html" charset="iso-8859-1"
                                href="blog/un-blog-de-prueba" title="Un Blog De Prueba">Un Blog De Prueba</a></h6>
                        <footer class="post-footer clearfix">
                            <div class="post-comment-count">1</div>
                            <div class="post-info">
                                <time datetime="10-02-2024">10-02-2024</time>
                            </div>
                        </footer>
                    </div>
                </article>
            </div>
            <div class="widget widget_recent_comments">
                <div class="section-title">
                    <h4>Comentarios Recientes</h4>
                </div>
                <ul id="recentcomments">
                    <li class="recentcomments">karracuka en <a hreflang="es" type="text/html" charset="iso-8859-1"
                            href="blog/un-blog-de-prueba#comment-1">Un Blog De Prueba</a></li>
                </ul>
            </div>
            <div class="widget widget_text">
                <div class="section-title"><a hreflang="es" type="text/html" charset="iso-8859-1" href="galeria"
                        title="Ver la Galería Completa">
                        <h4>Galería</h4>
                    </a></div>
                <div class="textwidget">
                    <div class="items-grid">
                        <div class="column gallery-item sixcol ">
                            <div class="featured-image">
                                <a href="img/vik-hotel-arena-blanca_14609041638.jpg" class="colorbox  cboxElement"
                                    data-group="gallery-111" title="Vik Hotel Arena Blanca"><img width="440"
                                        height="330" src="img/vik-hotel-arena-blanca_14609041638.jpg"
                                        class="attachment-preview wp-post-image" alt="Vik Hotel Arena Blanca"></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Vik Hotel Arena Blanca</h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="column gallery-item sixcol last">
                            <div class="featured-image">
                                <a href="img/sirenis-punta-cana-resort-casino-y-aquagames_14695414456.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Sirenis Punta Cana Resort Casino &amp; Aquagames"><img width="440"
                                        height="330"
                                        src="img/sirenis-punta-cana-resort-casino-y-aquagames_14695414456.jpg"
                                        class="attachment-preview wp-post-image"
                                        alt="Sirenis Punta Cana Resort Casino &amp; Aquagames"></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Sirenis Punta Cana Resort Casino &amp; Aquagames</h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="clear"></div>
                        <div class="column gallery-item sixcol ">
                            <div class="featured-image">
                                <a href="img/sirenis-punta-cana-resort-casino-y-aquagames_14695409563.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Sirenis Punta Cana Resort Casino &amp; Aquagames"><img width="440"
                                        height="330"
                                        src="img/sirenis-punta-cana-resort-casino-y-aquagames_14695409563.jpg"
                                        class="attachment-preview wp-post-image"
                                        alt="Sirenis Punta Cana Resort Casino &amp; Aquagames"></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Sirenis Punta Cana Resort Casino &amp; Aquagames</h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="column gallery-item sixcol last">
                            <div class="featured-image">
                                <a href="img/sirenis-punta-cana-resort-casino-y-aquagames_14695409576.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Sirenis Punta Cana Resort Casino &amp; Aquagames"><img width="440"
                                        height="330"
                                        src="img/sirenis-punta-cana-resort-casino-y-aquagames_14695409576.jpg"
                                        class="attachment-preview wp-post-image"
                                        alt="Sirenis Punta Cana Resort Casino &amp; Aquagames"></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Sirenis Punta Cana Resort Casino &amp; Aquagames</h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="clear"></div>
                        <div class="column gallery-item sixcol ">
                            <div class="featured-image">
                                <a href="img/catalonia-riviera-maya-resort-y-spa_147005578017.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Catalonia Riviera Maya Resort &amp; Spa"><img width="440" height="330"
                                        src="img/catalonia-riviera-maya-resort-y-spa_147005578017.jpg"
                                        class="attachment-preview wp-post-image"
                                        alt="Catalonia Riviera Maya Resort &amp; Spa"></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Catalonia Riviera Maya Resort &amp; Spa</h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="column gallery-item sixcol last">
                            <div class="featured-image">
                                <a href="img/sirenis-punta-cana-resort-casino-y-aquagames_14695410192.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Sirenis Punta Cana Resort Casino &amp; Aquagames"><img width="440"
                                        height="330"
                                        src="img/sirenis-punta-cana-resort-casino-y-aquagames_14695410192.jpg"
                                        class="attachment-preview wp-post-image"
                                        alt="Sirenis Punta Cana Resort Casino &amp; Aquagames"></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Sirenis Punta Cana Resort Casino &amp; Aquagames</h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="clear"></div>
                        <div class="column gallery-item sixcol ">
                            <div class="featured-image">
                                <a href="img/vik-hotel-arena-blanca_146090416315.jpg" class="colorbox  cboxElement"
                                    data-group="gallery-111" title="Vik Hotel Arena Blanca"><img width="440"
                                        height="330" src="img/vik-hotel-arena-blanca_146090416315.jpg"
                                        class="attachment-preview wp-post-image" alt="Vik Hotel Arena Blanca"></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Vik Hotel Arena Blanca</h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="column gallery-item sixcol last">
                            <div class="featured-image">
                                <a href="img/caribe-club-princess-beach-resort-y-spa_146953626016.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Caribe Club Princess Beach Resort &amp; Spa "><img width="440"
                                        height="330" src="img/caribe-club-princess-beach-resort-y-spa_146953626016.jpg"
                                        class="attachment-preview wp-post-image"
                                        alt="Caribe Club Princess Beach Resort &amp; Spa "></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Caribe Club Princess Beach Resort &amp; Spa </h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </aside>
    </div>

@endsection
