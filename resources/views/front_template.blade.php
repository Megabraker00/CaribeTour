<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://js.stripe.com https://*.js.stripe.com https://m.stripe.network https://maps.googleapis.com https://maps.gstatic.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://js.stripe.com; img-src 'self' data: https:; connect-src 'self' https://api.stripe.com https://js.stripe.com https://r.stripe.com https://q.stripe.com https://merchant-ui-api.stripe.com https://m.stripe.network https://cdn.jsdelivr.net https://maps.googleapis.com https://maps.gstatic.com; frame-src https://js.stripe.com https://*.js.stripe.com https://hooks.stripe.com https://m.stripe.network https://www.google.com https://maps.google.com; manifest-src 'self'; worker-src 'self' blob:;">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $pageTitle = trim($__env->yieldContent('title', 'Especialistas en viajes al Caribe'));
        $pageDescription = trim($__env->yieldContent('description', 'Descubre las mejores ofertas en vuelos y hoteles al Caribe. Viajes personalizados a Punta Cana, Riviera Maya y más con CaribeTour.'));
        $ogTitle = trim($__env->yieldContent('og_title', $pageTitle));
        $ogDescription = trim($__env->yieldContent('og_description', $pageDescription));
        $robots = trim($__env->yieldContent('robots', 'index, follow'));
        $isHttpError = trim($__env->yieldContent('http_error')) !== '';
        $publicCrumbs = request()->route() && ! request()->routeIs('inicio') && ! $isHttpError
            ? \App\Support\PublicBreadcrumbs::items()
            : [];
    @endphp
    <meta name="robots" content="{{ $robots }}" />
    <meta name="application-name" content="CaribeTour.es" />

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Caribetour.es">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    <!-- App -->
    <meta name="theme-color" content="#ff9000">
    <meta name="author" content="CaribeTour.es">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Apple: instalación / modo pantalla completa -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="CaribeTour">

    <title>{{ $pageTitle }} | CaribeTour.es</title>
    <meta name="description" content="{{ $pageDescription }}" />
    <link rel="canonical" href="{{ url()->current() }}" />

    @if (count($publicCrumbs) > 1)
        <x-json-ld :data="\App\Support\StructuredData::breadcrumbList($publicCrumbs)" />
    @endif
    @stack('jsonld')

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Performance -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Bootstrap 5.0.2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Signika:wght@400;600&family=Open+Sans:ital,wght@0,400;0,600;1,400&display=swap">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet"/>
    <style>
        a {
            text-decoration: none;
        }

        *:focus:not(:focus-visible) {
            outline: none;
            box-shadow: none;
        }

        *:focus-visible {
            border-color: #ff9000;
            outline: 2px solid #ff9000;
            box-shadow: 0 0 0 .25rem #ff900026;
        }

        .btn:focus:not(:focus-visible) {
            box-shadow: none;
        }

        footer a,
        footer a:visited,
        footer a:hover,
        footer a:focus {
            color: #fff;
        }

        .tour-search-form {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.125);
            color: #444;
        }

        .tour-search-form .form-title,
        .tour-search-form input,
        .tour-search-form select {
            background: #fff;
            color: #444;
            box-shadow: none;
        }

        .tour-search-form input,
        .tour-search-form select {
            border: 1px solid #dee2e6;
        }

        .tour-search-form .form-title {
            background: rgba(0, 0, 0, 0.03);
        }

        .tour-search-form .form-title h5,
        .tour-search-form label {
            color: #444;
            text-shadow: none;
        }

        .tour-search-form .form-control:focus-visible,
        .tour-search-form .form-select:focus-visible {
            color: #444;
            background-color: #fff;
        }

        .tour-search-form .btn-container {
            border-color: transparent;
            box-shadow: none;
        }

        .home-carousel .carousel-caption {
            right: 0;
            bottom: 0;
            left: 0;
            padding: 2.5rem 1.25rem 2.25rem;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.78), rgba(0, 0, 0, 0));
            text-align: left;
        }

        .home-carousel .carousel-caption h5,
        .home-carousel .carousel-caption p,
        .home-carousel a.home-slide-link,
        .home-carousel a.home-slide-link:hover,
        .home-carousel a.home-slide-link:focus {
            color: #fff;
        }

        .home-carousel .carousel-item img {
            width: 100%;
            height: auto;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            object-position: center;
        }
    </style>

    <link rel="preload" as="image" href="{{ asset('images/site_bg.jpg') }}" fetchpriority="high">

    <link rel="preload" as="image" href="{{ asset('images/logo.png') }}" fetchpriority="high">

    @yield('head')

</head>
<body class="min-vh-100 d-flex flex-column">

    <header class="bg-palm">

        <!-- container -->
        <div class="container my-3">

            <!-- header -->
            <nav class="navbar navbar-expand-lg bg-transparent">

                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="{{ route('inicio') }}">
                    <img src="{{ asset('images/logo.png') }}" style="min-height: 62px" alt="CaribeTour Logo"
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

        @if ($publicCrumbs !== [])
            <x-breadcrumbs :crumbs="$publicCrumbs" />
        @endif

        @yield('content')
    </main>

    <!-- footer -->
    <footer class="py-3 bg-palm text-light">
        <div class="container">
            <div class="row"><span>CaribeTour.es © 2024 - Todos los derechos reservados.</span></div>
            <div>
                <a href="#" title="Condiciones de uso">Condiciones de uso</a> | <a href="#" title="Aviso Legal">Aviso Legal</a> | <a href="#" title="Política de Privacidad">Política de Privacidad</a> | <a href="#" title="Uso de Cookies">Uso de Cookies</a> | <a href="{{ route('reservation.lookup') }}" title="Consultar reserva">Consultar reserva</a>
            </div>
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
    @yield('custom-js')
    @stack('scripts')
    <script>
        document.querySelectorAll('form[data-disable-on-submit]').forEach(function (form) {
            form.addEventListener('submit', function () {
                form.querySelectorAll('[type="submit"]').forEach(function (button) {
                    button.disabled = true;
                });
            });
        });
    </script>

</body>
</html>