<!DOCTYPE html>
<html lang="es-ES" dir="ltr">
    <head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="index, follow, All" />
        <meta name="language" content="Spanish" />
        <meta name="copyright" content="Copyright &copy; <?php echo date('Y'); ?> - CaribeTour.es" />
        <meta name="revisit" content="1 month" />
        <meta name="application-name" content="CaribeTour.es" /><!-- TODO: cambiarlo por variables de entorno -->
        <meta name="geo.placename" content="Madrid" />
        <meta http-equiv="content-language" content="es-ES" />
        <meta name="geo.country" content="ES" />
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="Caribetour.es"/><!-- TODO: cambiarlo por variables de entorno -->
        <meta property="og:url" content="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]; ?>" /> <!-- TODO: cambiarlo por variables de entorno -->
        <meta property="og:title" content="Caribetour.es | Especialistas en el Caribe" />
        <meta name="title" content="CaribeTour.es: Especialistas en el Caribe" />
        <meta name="DC.title" content="CaribeTour.es: Especialistas en el Caribe" />
        <title>CaribeTour.es | Especialistas en el Caribe</title>        
        <meta name="description" content="CaribeTour.es | Agencia especializada en el Caribe y sus destinos" />
        <meta name="keywords" content="CaribeTour.es | Agencia especializada en el Caribe y sus destinos" />
        <!--[if lt IE 9]>
            <script type="text/javascript" src="http://www.caribetour.es/js/jquery/html5.js"></script>
		<![endif]-->
        <base href="http://localhost/caribetour/"> <!-- TODO: cambiarlo por variables de entorno -->
        <link rel="canonical" href="http://www.caribetour.es/" /><!-- TODO: cambiarlo por variables de entorno -->
		<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('images/apple-touch-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('images/apple-touch-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('images/apple-touch-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/apple-touch-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('images/apple-touch-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/apple-touch-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/apple-touch-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/apple-touch-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon-180x180.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('images/favicon-32x32.png') }}" sizes="32x32">
        <link rel="icon" type="image/png" href="{{ asset('images/favicon-194x194.png') }}" sizes="194x194">
        <link rel="icon" type="image/png" href="{{ asset('images/favicon-96x96.png') }}" sizes="96x96">
        <link rel="icon" type="image/png" href="{{ asset('images/android-chrome-192x192.png') }}" sizes="192x192">
        <link rel="icon" type="image/png" href="{{ asset('images/favicon-16x16.png') }}" sizes="16x16">
        <link rel="manifest" href="{{ asset('images/manifest.json') }}">
        <link rel="mask-icon" href="{{ asset('images/safari-pinned-tab.svg') }}" color="#ff9000">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content="{{ asset('images/mstile-144x144.png') }}">
        <meta name="theme-color" content="#ff9000">        
        <meta name="apple-touch-fullscreen" content="yes" />
        <link rel='stylesheet' id='colorbox-css'  href="{{ asset('css/colorbox.css?ver=3.7.1') }}" type='text/css' media='all' />
        <link rel='stylesheet' id='jquery-ui-datepicker-css'  href="{{ asset('css/datepicker.css?ver=3.7.1') }}" type='text/css' media='all' />
        <link rel='stylesheet' id='general-css'  href="{{ asset('css/style.css?ver=3.7.1') }}" type='text/css' media='all' />
		<script type='text/javascript' src="{{ asset('js/jquery.js?ver=1.10.2') }}"></script>
        <script type='text/javascript' src="{{ asset('js/jquery-migrate.min.js?ver=1.2.1') }}"></script>
        <script type='text/javascript' src="{{ asset('js/comment-reply.min.js?ver=3.7.11') }}"></script>
        <script type='text/javascript' src="{{ asset('js/jquery.hoverIntent.min.js?ver=3.7.11') }}"></script>
        <script type='text/javascript' src="{{ asset('js/jquery.ui.touchPunch.js?ver=3.7.11') }}"></script>
        <script type='text/javascript' src="{{ asset('js/jquery.colorbox.min.js?ver=3.7.11') }}"></script>
        <script type='text/javascript' src="{{ asset('js/jquery.placeholder.min.js?ver=3.7.11') }}"></script>
        <script type='text/javascript' src="{{ asset('js/jquery.themexSlider.js?ver=3.7.11') }}"></script>
        <script type='text/javascript' src="{{ asset('js/jquery.textPattern.js?ver=3.7.11') }}"></script>
        <style type="text/css">body, .site-container{}body, input, select, textarea{font-family:Open Sans, Arial, Helvetica, sans-serif;}h1,h2,h3,h4,h5,h6, .button, .header-menu a, .woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,.woocommerce #content input.button,.woocommerce-page a.button,.woocommerce-page button.button,.woocommerce-page input.button,.woocommerce-page #respond input#submit,.woocommerce-page #content input.button{font-family:Signika, Arial, Helvetica, sans-serif;}a, h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover, .header-menu ul ul a:hover, .header-menu > div > ul > li.current-menu-item > a,.header-menu > div > ul > li.current-menu-parent > a,.header-menu > div > ul > li.hover > a,.header-menu > div > ul > li:hover > a{color:#FF9000;}input[type="submit"], input[type="button"], .button, .colored-icon, .widget_recent_comments li:before, .ui-slider .ui-slider-range, .tour-itinerary .tour-day-number h5, .testimonials-slider .controls a.current, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover{background-color:#FF9000;}.header .logo a, .header .header-text h5, .header .social-links span, .header-menu a, .header-menu a span, .site-footer .row, .site-footer a, .header-widgets .widget, .header-widgets .widget a, .header-widgets .section-title h3{color:#FFFFFF;}.header-menu ul ul li, .header-menu > div > ul > li.current-menu-item > a, .header-menu > div > ul > li.current-menu-parent > a, .header-menu > div > ul > li.hover > a, .header-menu > div > ul > li:hover > a{background-color:#FF9000;}::-moz-selection{background-color:#FF9000;}::selection{background-color:#FF9000;}</style>
        <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
		<script type="text/javascript">
            WebFontConfig = {google: { families: [ "Signika:400,600","Open Sans:400,400italic,600" ] } };
            (function() {
                var wf = document.createElement("script");
                wf.src = ("https:" == document.location.protocol ? "https" : "http") + "://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";
                wf.type = "text/javascript";
                wf.async = "true";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(wf, s);
            })();
        </script>
</head>
<body class="home page page-id-96 page-template-default">
	<div class="container site-container">
		<header class="container site-header">
			<div class="substrate top-substrate"><img src="{{ asset('images/site_bg.jpg') }}" class="fullwidth" alt="Imagen de fondo" /></div>
			<!-- background -->
            <!-- supheader -->
			
            <div class="row supheader">
                <div class="logo">
                    <a hreflang="es" type="text/html" charset="UTF-8" href="index.php" rel="home">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo CaribeTour.es" title="CaribeTour.es" />
                    </a>
                </div>
                            <!-- logo -->
                <div class="social-links">
                    <a class="facebook" href="https://www.facebook.com/caribetour.es" target="_blank" title="Siguenos en Facebook"></a>
                    <a class="twitter" href="https://twitter.com/CaribeToures" target="_blank" title="Siguenos en Twitter"></a>
                    <a class="google" href="https://plus.google.com/u/0/108828972964279107123/posts" target="_blank" title="Siguenos en Google+"></a>
                </div>
                            <!-- social links -->
                <nav class="header-menu">
                    <div class="menu">
                        <ul id="menu-main-menu" class="menu">
                            <li id="menu-item-97" class="menu-item"><a hreflang="es" type="text/html" charset="UTF-8" href="inicio" title="Inicio">INICIO</a></li>
                            <li id="menu-item-306" class="menu-item"><a hreflang="es" type="text/html" charset="UTF-8" href="servicios" title="Nuestros Servicios">SERVICIOS</a></li>
                            <li id="menu-item-122" class="menu-item"><a hreflang="es" type="text/html" charset="UTF-8" href="destinos" title="Todos los Destinos">DESTINOS</a></li>
                            <li id="menu-item-120" class="menu-item"><a hreflang="es" type="text/html" charset="UTF-8" href="blog" title="Nuestro Blog">BLOG</a></li>
                            <li id="menu-item-121" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-121"><a hreflang="es" type="text/html" charset="UTF-8" href="galeria" title="Galer&iacute;a de Imagenes">GALERIA</a></li>
                            <li id="menu-item-100" class="menu-item"><a hreflang="es" type="text/html" charset="UTF-8" href="contactos" title="Contacta con Nosotros">CONTACTO</a></li>
                        </ul>
                    </div>
                </nav>				
                <div class="clear"></div>
                <div>
                <noscript>
                    <p></p>
                    <p style="color:#FFFFFF">Bienvenido a CaribeTour.es<br />
                    La p&aacute;gina que est&aacute;s viendo requiere el uso de JavaScript para su correcto funcionamiento. 
                    Si lo has deshabilitado intencionadamente, por favor vuelve a activarlo.</p>
                </noscript>
                </div>
                <div class="select-menu select-field">
                    <select>
                        <option value="inicio" id="menu"><label for="menu">Men&uacute;</label></option>
                        <option value="inicio" id="inicio"><label for="inicio">Inicio</label></option>
                        <option value="servicios" id="servicios"><label for="servicios">Servicios</label></option>
                        <option value="destinos" id="destinos"><label for="destinos">Destinos</label></option>
                        <option value="blog" id="blog"><label for="blog">Blog</label></option>
                        <option value="galeria" id="galeria"><label for="galeria">Galer&iacute;a</label></option>
                        <option value="contacto" id="contacto"><label for="contacto">Contacto</label></option>
                    </select>
                <span>&nbsp;</span>
                </div>
                            <!--/ select menu-->						
            </div>

			<!-- /supheader -->
          <div class="row subheader">
            <div class="threecol column subheader-block">
              <!-- tour search form -->
                    <?php //include("includes/buscador.php");?>	
                    <h2>Buscador</h2>
              <!-- /tour search form -->	
            </div>
                <div class="ninecol column subheader-block last">
                  <div class="main-slider-container content-slider-container">
                    <div class="content-slider main-slider" id="slider" style="position:relative">
                      <ul>
                        @foreach ($featured_products as $f_product)
                        <li>
                            <div class="featured-image">
                                <a hreflang="es" type="text/html" charset="UTF-8" href="destinos/{{$f_product->category->name}}/{{$f_product->category->name}}">
                                <div class="etiqueta-categoria" id="etiqueta">
                                    {{$f_product->category->name}} desde <?php echo ('precio'); ?>&euro;
                                </div><img width="824" height="370" src="{{ asset('images/' . $f_product->mainImage()->path)}}" class="attachment-large wp-post-image" alt="{{$f_product->category->name}}" title="Ver Todos Los destinos de {{$f_product->category->name}}"/></a>
                            </div>
                        </li>
                        @endforeach
                      </ul>
                            <div class="arrow arrow-left content-slider-arrow"></div>
                            <div class="arrow arrow-right content-slider-arrow"></div>
                            <input type="hidden" class="slider-pause" value="7008" />
                            <input type="hidden" class="slider-speed" value="400" />
                            <input type="hidden" class="slider-effect" value="fade" />
                    </div>
                        <div class="block-background layer-1"></div>
                        <div class="block-background layer-2"></div>
                    </div>
                </div>
            </div>
			<!-- subheader -->
		  <div class="block-background header-background"></div>
		</header>
		<!-- header -->
		<section class="container site-content">
			<div class="row">
           	  <div class="eightcol column">
               	<div class="fivecol column">
                    	<img alt="CaribeTour.es | Explora el mundo" class="alignnone size-medium wp-image-21 demo-image" title="CaribeTour.es | Explora el mundo" src="{{ asset('images/explora.jpg') }}" />
				</div>
					<div class="sevencol column last">
                   	  <div class="section-title">
                       	<h1>Explora el Mundo con CaribeTour.es</h1>
                        </div>
El Caribe es una regi&oacute;n maravillosa que ofrece todos los atractivos necesarios: gente alegre y hospitalaria que le recibir&aacute; con una sonrisa, playas v&iacute;rgenes e interminables, bellas monta&ntilde;as repletas de vegetaci&oacute;n tropical, mares de colores &uacute;nicos y fondos sorprendentes, cuevas ancestrales, amaneceres sobrecogedores y una gastronom&iacute;a sabrosa hasta para los paladares m&aacute;s exquisitos.!
				</div>
                    <div class="clear"></div>
				</div>
                <div class="fourcol column last">
               	  <div class="content-slider testimonials-slider">
                   	<ul>
                   	  <li>
                        <article class="testimonial">
                          <div class="quote-text">
                            <div class="block-background">
                                        	Tuvimos el más increíble viaje de luna de miel en Punta Cana gracias a ustedes. Sin dudas el viaje superó con creces nuestras expectativas. Gracias!
                            </div>
                                    </div>
                                    <h6 class="quote-author">Maria Taveras</h6>
                                </article>
					  </li>
							<li>
                                <article class="testimonial">
                                    <div class="quote-text">
                                        <div class="block-background">
                                        	Todo fue absolutamente increíble y todos los detalles eran simplemente perfecto. han hecho todo el viaje sin complicaciones! El mejor viaje que he tenido.
										</div>
                                    </div>
                                    <h6 class="quote-author">Juan Fornel Jimenez</h6>
                                </article>
					  </li>
                            <li>
                                <article class="testimonial">
                                    <div class="quote-text">
                                        <div class="block-background">
                                        Thank you for the marvelous trip you arranged in the Dominican Republic. We could never have put together such a well-planned visit by ourselves. Amazing!
                                        </div>
                                    </div>
                                    <h6 class="quote-author">Lisa Blackwood</h6>
                                </article>
					  </li>
						</ul>
						<input type="hidden" class="slider-pause" value="0" /><input type="hidden" class="slider-speed" value="400" />
				  </div>
				</div>
                <div class="clear"></div>
    		</div>
    	</section>
    	<section class="container content-section">
        	<div class="substrate section-substrate">
            	<img src="{{ asset('images/background_1.jpg') }}" class="fullwidth" alt="" />
			</div>
			<div class="row">
            	<div class="items-grid">
                    @php
                        $i = 1;
                    @endphp
                	@foreach ($tours as $tour)         
                	<!-- TODO: Mejorar este if -->
                    @if ($i % 4 == 0)
                        <div class="column threecol last">
                    @else
                        <div class="column threecol">
                    @endif
                	    <div class="tour-thumb-container">
                	      <div class="tour-thumb">
                	        <a hreflang="es" type="text/html" charset="UTF-8" href="destinos/{{ $tour->category->slug }}/{{ $tour->category->slug }}/{{ $tour->slug }}"><img width="440" height="330" src="{{ asset('images/' . $tour->mainImage()->path)}}" class="attachment-preview wp-post-image" alt="{{ $tour->name }}" title="{{ $tour->name }}" /></a>
                	        <div class="tour-caption">
                	          <h5 class="tour-title"><a hreflang="es" type="text/html" charset="UTF-8" href="destinos/{{ $tour->category->slug }}/{{ $tour->category->slug }}/{{$tour->slug}}" title="{{ $tour->name }}">{{ $tour->name }}</a></h5>
                                <div class="tour-meta">
                                    <div class="tour-destination">
                                        <div class="colored-icon icon-2"></div>
                                        <a hreflang="es" type="text/html" charset="UTF-8" href="destinos/{{ $tour->category->slug }}/{{ $tour->category->slug }}/patata" rel="tag" title="Ver todos los destinos de {{ $tour->category->name }}">{{ $tour->category->name }}</a>
                                    </div>
								    <div class="colored-icon icon-3"></div><?php echo ('dblPrecio'); ?>&euro;
							    </div>
							  </div>			
						    </div>
						    <div class="block-background"></div>
						  </div>
					      </div>
                          @if ($i++ % 4 == 0) <div class="clear"></div> @endif

                      @endforeach
                      <div class="clear"></div>
				</div>
			</div>
		</section>
	  <section class="container site-content">
       	<div class="row">
       	  <div class="threecol column">
           	<div class="section-title">
           	  <a hreflang="es" type="text/html" charset="UTF-8" href="blog" title="Blogs de Viajes"><h2>Blogs de Viajes</h2></a>
            </div>
                <div class="featured-blog">
               	  <article class="post-112 post type-post status-publish format-standard hentry category-guides tag-amet tag-dolor tag-lorem post">
					<div class="featured-image">
					  <a hreflang="es" type="text/html" charset="UTF-8" href="blog/{{ $blog->slug }}"><img width="440" height="299" src="img/<?php echo 'strImagen'; ?>" class="attachment-normal wp-post-image" alt="Imagen de {{ ucwords($blog->name) }}" title="{{ ucwords($blog->name) }}" /></a>
                    </div>
						<div class="post-content">
						  <h5 class="post-title">
                            
                           	<a hreflang="es" type="text/html" charset="UTF-8" href="blog/{{ $blog->slug }}" title="{{ ucwords($blog->name) }}">{{ ucwords($blog->name) }}</a>
                          </h5>
							<p>{{ substr($blog->content, 0, 121); }}.[...]</p>
                    </div>
					<footer class="post-footer clearfix">
					  <a hreflang="es" type="text/html" charset="UTF-8" href="blog/{{ $blog->slug }}" class="button small" title="Leer mas sobre {{ ucwords($blog->name) }}">Leer M&aacute;s</a>
                      <div class="post-comment-count"><?php echo '7'; ?></div>
                        <div class="post-info">
						  <time datetime="{{ date('d-m-Y', strtotime($blog->created_at)) }}">{{ date('d-m-Y', strtotime($blog->created_at)) }}</time>
					  </div>
					</footer>
				</article>
			</div>
		</div>
		<div class="sixcol column">
        	<div class="section-title">
            	<a hreflang="es" type="text/html" charset="UTF-8" href="servicios/excusiones" title="Ver la Galer&iacute;a Completa"><h2>Excursiones</h2></a>
			</div>
			<div class="items-grid">

                @php
                    $n = 1;
                @endphp

                @foreach ($featured_excursions as $f_excursion)
                    @if ($n % 3 == 0)
                        <div class="column gallery-item fourcol last">
                    @else
                        <div class="column gallery-item fourcol">
                    @endif
                        <div class="featured-image">
                        <a hreflang="es" type="text/html" charset="UTF-8" href="img/<?php echo 'strImagen'; ?>" class="colorbox " data-group="gallery-111" title="{{ $f_excursion->name }}"><img width="440" height="330" src="img/<?php echo 'strImagen'; ?>" class="attachment-preview wp-post-image" alt="Imagen de {{ $f_excursion->name }}" /></a>
                        <a class="featured-image-caption hidden-caption" href="#"><h6>{{ $f_excursion->name }}</h6></a>
                        </div>
                        <div class="block-background"></div>
                    </div>
                    

                    @if ($n++ % 3 == 0) <div class="clear"></div> @endif

                @endforeach

				<div class="clear"></div>
			</div>
		</div>
        <div class="threecol column last">
			<div class="widget widget-twitter">
            	<div class="section-title"><h2>&Uacute;ltimos Tweets</h2></div>
                <div id="tweets">
                    <a class="twitter-timeline" href="https://twitter.com/CaribeToures" data-widget-id="508241026889162754">Tweets por @CaribeToures</a> 
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </div>
			</div>
		</div>
        <div class="clear"></div>
	</div>		
		</section>
		<!-- content -->
		<!-- footer -->
        
        <footer class="container site-footer">		
            <div class="row">
                <div class="copyright">
                    CaribeTour.es &copy; <?php echo date('Y'); ?> | Todos los derechos reservados.
                </div><br />
                <div class="copyright">
                    <a hreflang="es" type="text/html" charset="UTF-8" href="legal/<?php echo 'strSEO'; ?>" target="_blank" title="<?php echo 'strNombre'; ?>"><?php echo 'strNombre'; ?></a>
                </div>
                <div class="menu">
                    <ul id="menu-footer-menu" class="menu">
                        <li id="menu-item-98" class="menu-item"><a hreflang="es" type="text/html" charset="UTF-8" href="index.php" title="Inicio">INICIO</a></li>
                        <li id="menu-item-307" class="menu-item"><a hreflang="es" type="text/html" charset="UTF-8" href="servicios" title="Nuestros Servicios">SERVICIOS</a></li>
                        <li id="menu-item-217" class="menu-item"><a hreflang="es" type="text/html" charset="UTF-8" href="destinos" title="Todos los Destinos">DESTINOS</a></li>
                        <li id="menu-item-218" class="menu-item"><a hreflang="es" type="text/html" charset="UTF-8" href="blog" title="Nuestro Blog">BLOG</a></li>
                        <li id="menu-item-216" class="menu-item"><a hreflang="es" type="text/html" charset="UTF-8" href="galeria" title="Galer&iacute;a de Imagenes">GALERIA</a></li>
                        <li id="menu-item-99" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-99"><a hreflang="es" type="text/html" charset="UTF-8" href="contactos" title="Contacta con Nosotros">CONTACTOS</a></li>
                    </ul>
                </div>
            </div>
        </footer>

		<!-- footer -->
		<div class="substrate bottom-substrate">
			<img src="{{ asset('images/site_bg.jpg') }}" class="fullwidth" alt="Imagen de fondo" />
        </div>
	</div>
	<script type='text/javascript' src="{{ asset('js/jquery.ui.core.min.js?ver=1.10.3') }}"></script>
    <script type='text/javascript' src="{{ asset('js/jquery.ui.widget.min.js?ver=1.10.3') }}"></script>
    <script type='text/javascript' src="{{ asset('js/jquery.ui.mouse.min.js?ver=1.10.3') }}"></script>
    <script type='text/javascript' src="{{ asset('js/jquery.ui.slider.min.js?ver=1.10.3') }}"></script>
    <script type='text/javascript' src="{{ asset('js/jquery.ui.datepicker.min.js?ver=1.10.3') }}"></script>
</body>
</html>