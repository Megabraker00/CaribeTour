@extends('main_template')

@section('title', 'Unos destinos de puta madre')

@section('site-content')

    <div class="row">
        <div class="column eightcol">
            <article
                class="post-112 post type-post status-publish format-standard hentry category-guides tag-amet tag-dolor tag-lorem post full-post">
                <div class="post-featured-image">
                    <div class="featured-image">
                        <a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}/{{ $post->slug }}"
                            title="{{ $post->name }}"><img width="768" height="522" src="img/"
                                class="attachment-wide wp-post-image" alt="{{ $post->name }}"></a>
                    </div>
                </div>
                <div class="post-content">
                    <div class="section-title">
                        <h1>
                            <a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}/{{ $post->slug }}">
                                {{ $post->name }}
                            </a>
                        </h1>
                    </div>
                    Esto es la descripción del blog
                </div>
                <footer class="post-footer clearfix">
                    <div class="post-comment-count">1</div>
                    <div class="post-info">Por <strong>angel</strong> el 10-02-2024</div>
                </footer>
            </article>
            <div class="post-comments clearfix">
                <div class="section-title">
                    <h2>Deja tus Comentarios</h2>
                </div>
                <div id="respond" class="comment-respond">
                    <form action="coment_add.php" method="post" id="commentform" class="comment-form" name="comentarios"
                        onsubmit="return validacion();">
                        <div class="formatted-form">
                            <div class="message" id="nrequired" style="display:none;">Por favor indique su nombre completo
                            </div>
                            <div class="sixcol column">
                                <div class="field-container">
                                    <input id="nombre" name="strNombre" type="text" value="" size="30"
                                        maxlength="50" title="Indroduzca su Nombre" placeholder="Nombre" required="">
                                </div>
                            </div>
                            <div class="message" id="erequired" style="display:none;">Por favor indique un correo válido
                            </div>
                            <div class="sixcol column last">
                                <div class="field-container">
                                    <input id="email" name="strEmail" type="email" value=""
                                        title="Introduzca su Email" maxlength="60" size="30" placeholder="Email"
                                        required="">
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="message" id="mrequired" style="display:none;">Es necesario rellenar este campo</div>
                            <div class="field-container">
                                <textarea id="mensaje" name="strComentario" title="Introduzca sus Comentarios" maxlength="1000" cols="45"
                                    rows="8" placeholder="Comentario" required=""></textarea>
                            </div>
                        </div>
                        <p class="form-submit">
                            <input name="submit" type="submit" id="submit" value="Comentar">
                            <input type="hidden" name="idArticulo" value="1">
                            <input type="hidden" name="blogSeo" value="/caribetour/blog/un-blog-de-prueba">
                            <input type="hidden" name="MM_insert" value="comentarios">
                        </p><br>
                    </form>
                </div><!-- #respond -->
                <div class="section-title">
                    <h2>Comentarios</h2>
                </div>
                <div class="comments-list" id="comments">
                    <ul>
                        <li id="comment-1">
                            <div class="comment clearfix">
                                <div class="comment-text">
                                    <header class="comment-header clearfix">
                                        <h6 class="comment-author">karracuka</h6>
                                        <time class="comment-date" datetime="10-02-2024">10-02-2024</time>
                                        <span class="comment-reply-link"></span>
                                    </header>
                                    <p>esto es para ver si se ven los comentarios</p>
                                </div>
                            </div>
                        </li><!-- #comment-## -->
                    </ul>
                </div>
                <nav class="pagination comments-pagination"></nav>
            </div>
            <!-- post comments -->
        </div>
        <aside class="column fourcol last">
            <div class="widget widget-selected-posts">
                <div class="section-title">
                    <h4>Artículos Populares</h4>
                </div>
                <article
                    class="post-112 post type-post status-publish format-standard hentry category-guides tag-amet tag-dolor tag-lorem post clearfix">
                    <div class="post-featured-image">
                        <div class="featured-image">
                            <a hreflang="es" type="text/html" charset="iso-8859-1" href="blog/un-blog-de-prueba"><img
                                    width="440" height="299" src="img/" class="attachment-normal wp-post-image"
                                    alt="image_7"></a>
                        </div>
                    </div>
                    <div class="post-content">
                        <h6 class="post-title"><a hreflang="es" type="text/html" charset="iso-8859-1"
                                href="blog/un-blog-de-prueba">Un blog de Prueba</a></h6>
                        <footer class="post-footer clearfix">
                            <div class="post-comment-count">1</div>
                            <div class="post-info">
                                <time datetime="2012-11-24">10-02-2024</time>
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
                            href="blog/un-blog-de-prueba#comment-1">Un blog de Prueba</a></li>
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
                                <a href="img/sirenis-punta-cana-resort-casino-y-aquagames_146954095812.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Sirenis Punta Cana Resort Casino &amp; Aquagames"><img width="440"
                                        height="330"
                                        src="img/sirenis-punta-cana-resort-casino-y-aquagames_146954095812.jpg"
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
                                <a href="img/caribe-club-princess-beach-resort-y-spa_14695366112.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Caribe Club Princess Beach Resort &amp; Spa "><img width="440"
                                        height="330" src="img/caribe-club-princess-beach-resort-y-spa_14695366112.jpg"
                                        class="attachment-preview wp-post-image"
                                        alt="Caribe Club Princess Beach Resort &amp; Spa "></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Caribe Club Princess Beach Resort &amp; Spa </h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="clear"></div>
                        <div class="column gallery-item sixcol ">
                            <div class="featured-image">
                                <a href="img/natura-park-beach-eco-resort-y-spa_14694525191.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Natura Park Beach Eco Resort &amp; Spa"><img width="440" height="330"
                                        src="img/natura-park-beach-eco-resort-y-spa_14694525191.jpg"
                                        class="attachment-preview wp-post-image"
                                        alt="Natura Park Beach Eco Resort &amp; Spa"></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Natura Park Beach Eco Resort &amp; Spa</h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="column gallery-item sixcol last">
                            <div class="featured-image">
                                <a href="img/vik-hotel-arena-blanca_146090416316.jpg" class="colorbox  cboxElement"
                                    data-group="gallery-111" title="Vik Hotel Arena Blanca"><img width="440"
                                        height="330" src="img/vik-hotel-arena-blanca_146090416316.jpg"
                                        class="attachment-preview wp-post-image" alt="Vik Hotel Arena Blanca"></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Vik Hotel Arena Blanca</h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="clear"></div>
                        <div class="column gallery-item sixcol ">
                            <div class="featured-image">
                                <a href="img/sirenis-punta-cana-resort-casino-y-aquagames_14695414444.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Sirenis Punta Cana Resort Casino &amp; Aquagames"><img width="440"
                                        height="330"
                                        src="img/sirenis-punta-cana-resort-casino-y-aquagames_14695414444.jpg"
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
                                <a href="img/vik-hotel-arena-blanca_146090416310.jpg" class="colorbox  cboxElement"
                                    data-group="gallery-111" title="Vik Hotel Arena Blanca"><img width="440"
                                        height="330" src="img/vik-hotel-arena-blanca_146090416310.jpg"
                                        class="attachment-preview wp-post-image" alt="Vik Hotel Arena Blanca"></a>
                                <a class="featured-image-caption none-caption" href="#">
                                    <h6>Vik Hotel Arena Blanca</h6>
                                </a>
                            </div>
                            <div class="block-background"></div>
                        </div>
                        <div class="clear"></div>
                        <div class="column gallery-item sixcol ">
                            <div class="featured-image">
                                <a href="img/catalonia-riviera-maya-resort-y-spa_14700557781.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Catalonia Riviera Maya Resort &amp; Spa"><img width="440" height="330"
                                        src="img/catalonia-riviera-maya-resort-y-spa_14700557781.jpg"
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
                                <a href="img/caribe-club-princess-beach-resort-y-spa_14695362595.jpg"
                                    class="colorbox  cboxElement" data-group="gallery-111"
                                    title="Caribe Club Princess Beach Resort &amp; Spa "><img width="440"
                                        height="330" src="img/caribe-club-princess-beach-resort-y-spa_14695362595.jpg"
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
