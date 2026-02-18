<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow" />
    <meta name="application-name" content="CaribeTour.es" />

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Caribetour.es">
    <meta property="og:title" content="@yield('og_title', 'Viaja al Caribe con CaribeTour')">
    <meta property="og:description" content="@yield('og_description', 'Ofertas increíbles en vuelos y hoteles. ¡Reserva tu paraíso hoy!')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Viaja al Caribe con CaribeTour')">
    <meta name="twitter:description" content="@yield('og_description', 'Ofertas increíbles en vuelos y hoteles. ¡Reserva tu paraíso hoy!')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    <!-- //TODO: mejorar content-security-policy e incluir a todos los js y css de vendors autorizados -->
    <!--<meta http-equiv="Content-Security-Policy" content="default-src 'self'">-->

    <!-- App -->
    <meta name="theme-color" content="#ff9000">
    <meta name="author" content="CaribeTour.es">

    <title>@yield('title', 'Especialistas en viajes al Caribe') | CaribeTour.es</title>
    <meta name="description" content="@yield('title', 'Descubre las mejores ofertas en vuelos y hoteles al Caribe. Viajes personalizados a Punta Cana, Riviera Maya y más con CaribeTour.')" />
    <link rel="canonical" href="{{ url()->current() }}" />

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('images/manifest.json') }}">

    
    <!-- Performance -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Bootstrap 5.0.2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;1,400&family=Signika:wght@400;600&display=swap" media="print" onload="this.media='all'">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />

    <link rel="preload" as="image" href="{{ asset('images/site_bg.jpg') }}" fetchpriority="high">

    <link rel="preload" as="image" href="{{ asset('images/logo.png') }}" fetchpriority="high">

    @yield('head')

</head>
<body class="min-vh-100 d-flex flex-column">

    <!-- NOTA: añadir el atributo loading="lazy" y decoding="async" a las imágenes que no sean críticas para mejorar el rendimiento -->

    <header class="bg-palm">

        <!-- container -->
        <div class="container py-2">

            <!-- header -->
            <nav class="navbar navbar-expand-lg bg-transparent">

                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="{{ route('inicio') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="CaribeTour Logo"
                    width="100%"
                    fetchpriority="high">
                </a>

                <!-- Botón hamburguesa -->
                <button class="navbar-toggler" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                        aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menú -->
                <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                    <ul class="navbar-nav nav nav-pills gap-2">

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
            </nav>
            <!-- /header -->

            @yield('hero')

        </div>
        <!-- /container -->

    </header>

    <main class="flex-fill top-bottom-shadow">

        @if (!request()->routeIs('inicio'))
            <x-breadcrumbs />
        @endif

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

    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .catch(err => console.error('[serviceWorker] Error registering the SW', err));
            });
        }
    </script>

</body>
</html>