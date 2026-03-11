

    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const options = {
        clientSecret: "{{ $clientSecret }}",
        appearance: { theme: 'flat' },
    };

    const elements = stripe.elements(options);
    const paymentElement = elements.create("payment");
    paymentElement.mount("#payment-element");

    const form = document.getElementById('payment-form');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const {error} = await stripe.confirmPayment({
            elements,
            confirmParams: {
                // URL a la que vuelve el cliente tras pagar
                return_url: "{{ url('/reserva/' . $product->slug . '/pago/ok') }}",
            },
        });

        if (error) {
            const messageContainer = document.querySelector('#error-message');
            messageContainer.textContent = error.message;
        }
    });