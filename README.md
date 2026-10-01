# CaribeTour

Aplicación web de agencia de viajes (Laravel 10) para vender y gestionar tours, excursiones y servicios turísticos. El cliente reserva en la web pública y el equipo opera desde un panel de administración.

## Stack

- PHP 8.1+ y Laravel 10
- Blade + AdminLTE
- Stripe (pagos)
- Yajra DataTables
- Laravel Sanctum y l5-swagger (API)
- Laravel UI (login, registro y reset de contraseña)

## Dominio

El catálogo gira en torno a **productos** (tours, excursiones, hoteles, etc.):

1. Un producto tiene **itinerarios** (fecha, stock y precio).
2. Cada itinerario se parte en **segmentos** (salida/llegada y terminales).
3. Los precios pueden variar por tipo de pasajero (infante, niño, adulto, sénior).
4. Las **categorías** jerárquicas representan destinos (país → provincia).
5. Una **reserva** une titular, pasajeros, itinerario y pagos.

## Requisitos

- PHP 8.1+, Composer, MySQL
- Extensión `bcmath` (importes Stripe en céntimos)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configura la base de datos y las claves de Stripe en `.env`:

- `STRIPE_KEY_TEST` / `STRIPE_SECRET_KEY_TEST`
- `STRIPE_WEBHOOK_SECRET` (Dashboard de Stripe, endpoint `POST /api/stripe/webhook`)

```bash
php artisan migrate --seed
php artisan serve
```

## Flujo de reserva y pago

1. El cliente elige un tour e itinerario (`/reserva/{tour}/{itinerary}`).
2. Se validan plazas, pasajeros e itinerario; el stock se bloquea en transacción.
3. El pago se hace con Stripe. El PaymentIntent se reutiliza si el usuario recarga `/pago`.
4. El estado de la reserva pasa a pagado con el **webhook** de Stripe (no solo con la redirección).
5. El titular recibe un email de confirmación y puede consultar la reserva en `/reserva/consulta` con localizador y email.

## Accesos

- Web pública: `/`, destinos, servicios, blog, galería, contacto, reservas.
- Admin: `/admin` (requiere login).
- API v1: lectura pública de catálogo; altas/ediciones/borrados con `auth:sanctum`.
- DataTables del admin: sesión autenticada.

## Comandos útiles

```bash
composer format   # Pint (aplica estilo)
composer lint     # Pint en modo test
vendor/bin/phpunit
```

## Calidad

CI ejecuta `composer lint` y PHPUnit.
