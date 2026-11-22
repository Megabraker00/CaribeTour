@extends('front_template')
@section('title', 'Pago de la reserva')
@section('description', 'Revisa el importe y paga la reserva de forma segura.')
@section('robots', 'noindex, nofollow')

@section('content')
    <!-- container -->
    <section class="container my-4">

        <h1 class="h3 mb-4">Pago de la reserva</h1>

        <div class="row">

            <div class="col-md-6 col-sm-12 col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Resumen de tu reserva</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <p>
                            <img loading="lazy" src="{{ asset($product->mainImage?->path ?? 'images/image_12.jpg') }}" alt="Imagen del {{$product->name}}" title="{{$product->name}}" class="img-aspect-4-3 card-img img-fluid">
                            </p>
                            <h5 class="card-title" title="{{$product->name}}">{{$product->name}}</h5>
                            <div class="card-subtitle mb-2 text-muted" title="Resumen">
                                <ul class="tour-info">
                                    <li title="{{ $product->starsLabel() }}"><i class="bi bi-trophy-fill" aria-hidden="true"></i><strong>Categoría:</strong>
                                        @if ($product->stars() > 0)
                                            <span class="star-{{ $product->stars() }} fs-6" role="img" aria-label="{{ $product->starsLabel() }}"></span>
                                        @else
                                            <span class="text-muted small">Sin clasificar</span>
                                        @endif
                                    </li>
                                    <li><i class="bi bi-geo-alt-fill" aria-hidden="true"></i><strong>Destino:</strong> {{$product->category}} - {{$product->category?->parentCategory}}</li>
                                    <li><i class="bi bi-arrow-up-right-square-fill" aria-hidden="true"></i><strong>Salida:</strong> {{ ucfirst(\Carbon\Carbon::parse($productDeparture)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-arrow-down-left-square-fill" aria-hidden="true"></i><strong>Regreso:</strong> {{ ucfirst(\Carbon\Carbon::parse($productReturn)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-calendar-week-fill" aria-hidden="true"></i><strong>Duración:</strong> {{$days}} Días - {{$nights}} Noches</li>
                                    @if(!empty($product->meta['includes']))
                                    <i class="bi bi-ui-checks" aria-hidden="true"></i><strong>Incluye: </strong>
                                        <ul>
                                            @foreach($product->meta['includes'] as $include)
                                                <li>{{ $include }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <li title="Precio por persona"><i class="bi bi-tag-fill" aria-hidden="true"></i><strong class="fs-5">Precio por Persona: </strong><span class="fs-4 fw-bold">{{$price}}&euro;</span></li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="card-footer fs-3">                            
                             <strong>
                                <i class="bi bi-cash-stack" aria-hidden="true"></i> TOTAL:   <span title="Total a pagar">{{ $booking->formattedPassengersTotal() }}&euro;</span>
                            </strong>                            
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6 col-sm-12 col-lg-8">

                <h2>Ralizar Pago</h2>

                <form id="payment-form">
                    <div id="link-authentication-element"></div>
                    <div id="payment-element">
                    </div>
                    <button id="submit" class="btn btn-primary mt-4">
                        Pagar {{ $booking->formattedPassengersTotal() }}&euro;
                    </button>
                    <div id="error-message" class="text-danger mt-3" role="alert"></div>
                </form>

            </div>

        </div>

    </section>
    <!-- /container -->
@endsection
@push('scripts')
@php
    $paymentReturnUrl = route('reservation.payment.callback', [$product, $itinerary], true);
@endphp
<script src="https://js.stripe.com/v3/"></script>
<script>
    (function () {
        const messageContainer = document.querySelector('#error-message');
        const stripeKey = @json($stripeKey);
        const showError = function (message) {
            if (messageContainer) {
                messageContainer.textContent = message;
            }
        };

        if (!stripeKey || typeof Stripe !== 'function') {
            showError('No se ha podido cargar el formulario de pago. Recarga la página. Si usas un bloqueador, permite js.stripe.com y vuelve a intentarlo.');
            return;
        }

        const stripe = Stripe(stripeKey);
        let elements;

        try {
            elements = stripe.elements({
                clientSecret: @json($clientSecret),
            });
        } catch (error) {
            showError(error.message);
            return;
        }

        const paymentElement = elements.create('payment');
        paymentElement.on('loaderror', function (event) {
            const message = event.error && event.error.message
                ? event.error.message
                : 'No se ha podido mostrar el formulario de pago.';
            showError(message);
        });
        paymentElement.mount('#payment-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            const submitButton = document.getElementById('submit');
            if (submitButton) {
                submitButton.disabled = true;
            }

            const {error} = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: @json($paymentReturnUrl),
                },
            });

            if (error) {
                showError(error.message);
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
        });
    })();
</script>
@endpush