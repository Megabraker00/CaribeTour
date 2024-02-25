<!DOCTYPE html>
<html lang="es-ES" dir="ltr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
        <meta charset="iso-8859-1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="index, follow, All" />
        <meta name="language" content="Spanish" />
        <meta name="copyright" content="Copyright &copy; 2024 - CaribeTour.es" />
        <meta name="revisit" content="1 month" />
        <meta name="application-name" content="CaribeTour.es" />
        <meta name="geo.placename" content="Madrid" />
        <meta http-equiv="content-language" content="es-ES" />
        <meta name="geo.country" content="ES" />
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="Caribetour.es"/>
        <meta property="og:url" content="http://localhost/caribetour/servicios" />
        <meta property="og:title" content="@yield('title', 'Título por defecto') | Especialistas en el Caribe" />
        <meta name="title" content="@yield('title', 'Título por defecto') | Especialistas en el Caribe" />
        <meta name="DC.title" content="@yield('title', 'Título por defecto') | Especialistas en el Caribe" />
        <title>@yield('title', 'Título por defecto') | Especialistas en el Caribe</title>
        <meta name="description" content="@yield('title', 'Título por defecto') | Especialistas en el Caribe" />
        <meta name="keywords" content="Servicios de CaribeTour, Excursiones en el caribe, vuelos al caribe, hoteles en el caribe, rentacar en el caribe" />
        <!--[if lt IE 9]>
            <script type="text/javascript" src="http://www.caribetour.es/js/jquery/html5.js"></script>
		<![endif]-->
        <!--<base href="http://localhost/caribetour/">--> <!--TODO: corregir esto y que sea dinámico -->
        <link rel="canonical" href="http://www.caribetour.es/" />
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
        <script type='text/javascript' src="{{ asset('js/general.js?ver=3.7.11') }}"></script>
        <style type="text/css">body, .site-container{}body, input, select, textarea{font-family:Open Sans, Arial, Helvetica, sans-serif;}h1,h2,h3,h4,h5,h6, .button, .header-menu a, .woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,.woocommerce #content input.button,.woocommerce-page a.button,.woocommerce-page button.button,.woocommerce-page input.button,.woocommerce-page #respond input#submit,.woocommerce-page #content input.button{font-family:Signika, Arial, Helvetica, sans-serif;}a, h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover, .header-menu ul ul a:hover, .header-menu > div > ul > li.current-menu-item > a,.header-menu > div > ul > li.current-menu-parent > a,.header-menu > div > ul > li.hover > a,.header-menu > div > ul > li:hover > a{color:#FF9000;}input[type="submit"], input[type="button"], .button, .colored-icon, .widget_recent_comments li:before, .ui-slider .ui-slider-range, .tour-itinerary .tour-day-number h5, .testimonials-slider .controls a.current, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover{background-color:#FF9000;}.header .logo a, .header .header-text h5, .header .social-links span, .header-menu a, .header-menu a span, .site-footer .row, .site-footer a, .header-widgets .widget, .header-widgets .widget a, .header-widgets .section-title h3{color:#FFFFFF;}.header-menu ul ul li, .header-menu > div > ul > li.current-menu-item > a, .header-menu > div > ul > li.current-menu-parent > a, .header-menu > div > ul > li.hover > a, .header-menu > div > ul > li:hover > a{background-color:#FF9000;}::-moz-selection{background-color:#FF9000;}::selection{background-color:#FF9000;} .recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}
        </style>
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
    <body class="page page-id-295 page-template-default">
        <div class="container site-container">
            <!-- header -->
            <header class="container site-header">
                <div class="substrate top-substrate">
                    <img src="{{ asset('images/site_bg.jpg') }}" class="fullwidth" alt="Imagen de fondo" /><!-- background -->
				</div>
                <!-- supheader -->
                <div class="row supheader">

                    <!-- logo -->
                    <div class="logo">
                        <a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('inicio') }}" rel="home">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo CaribeTour.es" title="CaribeTour.es" />
                        </a>
                    </div>
                    <!-- /logo -->

                    <!-- social links -->
                    <div class="social-links">
                        <a class="facebook" href="https://www.facebook.com/caribetour.es" target="_blank" title="Siguenos en Facebook"></a>
                        <a class="twitter" href="https://twitter.com/CaribeToures" target="_blank" title="Siguenos en Twitter"></a>
                        <a class="google" href="https://plus.google.com/u/0/108828972964279107123/posts" target="_blank" title="Siguenos en Google+"></a>
                    </div>
                    <!-- /social links -->

                    <!-- header menu -->
                    <nav class="header-menu">
                        <div class="menu">
                            <ul id="menu-main-menu" class="menu">
                                <li id="menu-item-97" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('inicio') }}" title="Inicio">INICIO</a></li>
                                <li id="menu-item-306" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('servicios') }}" title="Nuestros Servicios">SERVICIOS</a></li>
                                <li id="menu-item-122" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('destinos') }}" title="Todos los Destinos">DESTINOS</a></li>
                                <li id="menu-item-120" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}" title="Nuestro Blog">BLOG</a></li>
                                <li id="menu-item-121" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-121"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('galeria') }}" title="Galer&iacute;a de Imagenes">GALERIA</a></li>
                                <li id="menu-item-100" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('contacto') }}" title="Contacta con Nosotros">CONTACTOS</a></li>
                            </ul>
                        </div>
                    </nav>
                    <!-- /header menu -->

                    <div class="clear">
                    </div>

                    <!-- no javascript -->
                    <div class="row">
                        <noscript>
                            <p></p>
                            <p style="color:#FFFFFF">Bienvenido a CaribeTour.es<br />
                            La p&aacute;gina que est&aacute;s viendo requiere el uso de JavaScript para su correcto funcionamiento. 
                            Si lo has deshabilitado intencionadamente, por favor vuelve a activarlo.</p>
                        </noscript>
                    </div>
                    <!-- /no javascript -->

                    <!-- select menu -->
                    <div class="select-menu select-field">
                        <select id="select-menu">
                            <option value="{{ route('inicio') }}" id="menu"><label for="menu">Men&uacute;</label></option>
                            <option value="{{ route('inicio') }}" id="inicio"><label for="inicio">Inicio</label></option>
                            <option value="{{ route('servicios') }}" id="servicios"><label for="servicios">Servicios</label></option>
                            <option value="{{ route('destinos') }}" id="destinos"><label for="destinos">Destinos</label></option>
                            <option value="{{ route('blogs') }}" id="blog"><label for="blog">Blog</label></option>
                            <option value="{{ route('galeria') }}" id="galeria"><label for="galeria">Galer&iacute;a</label></option>
                            <option value="{{ route('contacto') }}" id="contacto"><label for="contacto">Contactos</label></option>
                        </select>
                        <span>&nbsp;</span>
                    </div>
                    <!--/ select menu-->

                </div>
                <!-- /supheader -->
                
                @yield('supheader')

                <div class="block-background header-background"></div>
            </header>
            <!-- /header -->

            <!-- content -->
            <section class="container site-content">
                @yield('site-content')
            </section>
            <!-- /content -->
            
            <!-- footer -->
            <footer class="container site-footer">		
                <div class="row">
                    <div class="copyright">
                        CaribeTour.es &copy; 2024 | Todos los derechos reservados.
                    </div><br />
                    <div class="copyright">
                        <a hreflang="es" type="text/html" charset="iso-8859-1" href="legal/condiciones-de-uso" target="_blank" title="Condiciones De Uso">Condiciones De Uso</a> |
                        <a hreflang="es" type="text/html" charset="iso-8859-1" href="legal/aviso-legal" target="_blank" title="Aviso Legal">Aviso Legal</a> |
                        <a hreflang="es" type="text/html" charset="iso-8859-1" href="legal/politica-de-privacidad" target="_blank" title="Pol&iacute;tica De Privacidad">Pol&iacute;tica De Privacidad</a> |
                        <a hreflang="es" type="text/html" charset="iso-8859-1" href="legal/uso-de-cookies" target="_blank" title="Uso De Cookies">Uso De Cookies</a> |
                    </div>
                    <div class="menu">
                        <ul id="menu-footer-menu" class="menu">
                            <li id="menu-item-98" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('inicio') }}" title="Inicio">INICIO</a></li>
                            <li id="menu-item-307" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('servicios') }}" title="Nuestros Servicios">SERVICIOS</a></li>
                            <li id="menu-item-217" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('destinos') }}" title="Todos los Destinos">DESTINOS</a></li>
                            <li id="menu-item-218" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('blogs') }}" title="Nuestro Blog">BLOG</a></li>
                            <li id="menu-item-216" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('galeria') }}" title="Galer&iacute;a de Imagenes">GALERIA</a></li>
                            <li id="menu-item-99" class="menu-item"><a hreflang="es" type="text/html" charset="iso-8859-1" href="{{ route('contacto') }}" title="Contacta con Nosotros">CONTACTOS</a></li>
                        </ul>
                    </div>
                </div>
            </footer>
            <!-- /footer -->

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