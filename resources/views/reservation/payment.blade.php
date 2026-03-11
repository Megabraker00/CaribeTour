@extends('front_template')
@section('title', 'Elige los mejores destinos del caribe - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))

@section('content')
    <!-- container -->
    <section class="container my-4">

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
                                    <li title="Categoría: 5 estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-5 fs-6"></span> </li>
                                    <li><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> {{$product->category}} - {{$product->category?->parentCategory}}</li>
                                    <li><i class="bi bi-arrow-up-right-square-fill"></i><strong>Salida:</strong> {{ ucfirst(\Carbon\Carbon::parse($productDeparture)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-arrow-down-left-square-fill"></i><strong>Regreso:</strong> {{ ucfirst(\Carbon\Carbon::parse($productReturn)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-calendar-week-fill"></i><strong>Duración:</strong> {{$days}} Días - {{$nights}} Noches</li>
                                    @if(!empty($product->meta['includes']))
                                    <i class="bi bi-ui-checks"></i><strong>Incluye: </strong>
                                        <ul>
                                            @foreach($product->meta['includes'] as $include)
                                                <li>{{ $include }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <li title="Precio por persona"><i class="bi bi-tag-fill"></i><strong class="fs-5">Precio por Persona: </strong><span class="fs-4 fw-bold">{{$price}}&euro;</span></li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="card-footer fs-3">                            
                             <strong>
                                <i class="bi bi-cash-stack"></i> TOTAL:   <span title="Total a pagar">{{ number_format($price * 2, 2, ',', '.') }}&euro;</span>
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
                        Pagar {{ $booking->total_price }} €
                    </button>
                    <div id="error-message"></div>
                </form>

            </div>

        </div>

    </section>
    <!-- /container -->
@endsection
@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const stripeKey = "{{ env('STRIPE_KEY_TEST') }}";

        if (!stripeKey) {
            console.error("Error: STRIPE_KEY no detectada.");
            return;
        }

        window.stripe = Stripe(stripeKey); 
        const stripe = window.stripe;//Stripe(stripeKey);
        
        const options = {
            clientSecret: "{{ $clientSecret }}",
            //appearance: { theme: 'flat' },
        };

        const elements = stripe.elements(options);
        const paymentElement = elements.create("payment");
        
        setTimeout(() => {            
        
            // Verificamos si el elemento existe antes de montar
            const el = document.getElementById("payment-element");
            if (el) {
                paymentElement.mount("#payment-element");
                console.log('si se encontro el payment-element');
            } else {
                console.error("No se encontró el elemento #payment-element");
            }

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const {error} = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        //return_url: "{{ url('/reserva/' . $product->slug . '/' . $itinerary->id . '/pago/ok') }}",
                        return_url: "{{ route('reservation.payment.callback', [$product, $itinerary], true) }}",
                    },
                });

                if (error) {
                    const messageContainer = document.querySelector('#error-message');
                    messageContainer.textContent = error.message;
                }
            });

        }, 100);
    });
</script>
@endpush