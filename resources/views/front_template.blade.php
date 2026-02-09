<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow, All" />
    <meta name="revisit" content="1 month" />
    <meta name="application-name" content="CaribeTour.es" />

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Caribetour.es">
    <meta property="og:title" content="@yield('og_title', 'Viaja al Caribe con CaribeTour')">
    <meta property="og:description" content="@yield('og_description', 'Ofertas en vuelos y hoteles al Caribe')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Viaja al Caribe con CaribeTour')">
    <meta name="twitter:description" content="@yield('og_description', 'Ofertas en vuelos y hoteles al Caribe')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    <!-- //TODO: mejorar content-security-policy e incluir a todos los js y css de vendors autorizados -->
    <!--<meta http-equiv="Content-Security-Policy" content="default-src 'self'">-->
    <meta http-equiv="Cache-Control" content="no-cache">

    <!-- App -->
    <meta name="theme-color" content="#ff9000">
    <meta name="author" content="CaribeTour.es">

    <title>@yield('title', 'Título por defecto') | Especialistas en el Caribe</title>
    <meta name="description" content="@yield('title', 'Título por defecto') | Especialistas en el Caribe" />
    <link rel="canonical" href="{{ url()->current() }}" />

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

    
    <!-- Performance -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Bootstrap 5.0.2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;1,400&family=Signika:wght@400;600&display=swap">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />

    <style>
        body {
            /*padding-top: 70px;*/ /* espacio para navbar fijo */
        }
    </style>
</head>
<body class="min-vh-100 d-flex flex-column">

    <link rel="preload" as="image" href="{{ asset('images/site_bg.jpg') }}" fetchpriority="high">

    <!-- NOTA: añadir el atributo loading="lazy" y decoding="async" a las imágenes que no sean críticas para mejorar el rendimiento -->

    <header class="bg-palm">

        <!-- container -->
        <div class="container pt-4 pb-4">

            <!-- button-header -->
            <div class="d-flex flex-wrap justify-content-center align-items-center py-3 mb-4">

                <a href="{{ route('inicio') }}" rel="home" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                    <img src="{{ asset('images/logo.png') }}" alt="CaribeTour Logo" title="CaribeTour Logo">
                </a>

                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="{{ route('inicio') }}"
                        class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}">
                        <strong>INICIO</strong>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('destinos') }}"
                        class="nav-link {{ request()->routeIs('destinos*') ? 'active' : '' }}">
                        <strong>DESTINOS</strong>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('servicios') }}"
                        class="nav-link {{ request()->routeIs('servicios*') ? 'active' : '' }}">
                        <strong>SERVICIOS</strong>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('blogs') }}"
                        class="nav-link {{ request()->routeIs('blogs*') ? 'active' : '' }}">
                        <strong>BLOG</strong>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('galeria') }}"
                        class="nav-link {{ request()->routeIs('galeria') ? 'active' : '' }}">
                        <strong>GALERÍA</strong>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('contacto') }}"
                        class="nav-link {{ request()->routeIs('contacto') ? 'active' : '' }}">
                        <strong>CONTACTOS</strong>
                        </a>
                    </li>
                </ul>

            </div>
            <!-- /button-header -->

            <div class="row">

                @yield('hero')

            </div>

        </div>
        <!-- /container -->

    </header>

    <main class="flex-fill">
    @yield('content')
    </main>

    <!-- footer -->
    <footer class="py-4 bg-palm text-light">
        <div class="container">
            <div class="row"><a>CaribeTour.es © 2024 - Todos los derechos reservados.</a></div>
            <div class="row"><a>Condiciones de uso | Aviso Legal | Política de Privacidad | Uso de Cookies</a></div>
        </div>
    </footer>
    <!-- /footer -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>