<x-front-layout title="Order Payment">
    <style>
        /* Style for the payment form */
        #payment-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    
        /* Style for the Pay Now button */
        #submit {
            width: 100%;
            background-color: #007bff; /* Blue color */
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
    
        /* Hover effect for the button */
        #submit:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    
        /* Disable state for the button */
        #submit:disabled {
            background-color: #ccc; /* Grayed-out when disabled */
            cursor: not-allowed;
        }
    
        /* Spinner styling */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
    
        /* Animation for the spinner */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    
        /* Hidden class for elements */
        .hidden {
            display: none;
        }
    </style>
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <div id="payment-message" style="display: none;" class="alert alert-info"></div>

                     <!-- Display a payment form -->
                    <form id="payment-form">
                        <div id="payment-element">
                        <!--Stripe.js injects the Payment Element-->
                        </div>
                        <button id="submit">
                        <div class="spinner hidden" id="spinner"></div>
                        <span id="button-text">Pay now</span>
                        </button>
                        <div id="payment-message" class="hidden"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
   
    <script>
        // This is your test publishable API key.
        const stripe = Stripe("{{ config('services.stripe.publishable_key') }}");

        let elements;

        initialize();

        document
            .querySelector("#payment-form")
            .addEventListener("submit", handleSubmit);

        // Fetches a payment intent and captures the client secret
        async function initialize() {
    try {
        const response = await fetch("{{ route('stripe.paymentIntent.create', $order->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                "_token": "{{ csrf_token() }}"
            }),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error(errorData.error || 'An unexpected error occurred.');
            return;
        }

        const { clientSecret } = await response.json();
        elements = stripe.elements({ clientSecret });
        const paymentElement = elements.create("payment");
        paymentElement.mount("#payment-element");
    } catch (error) {
        console.error('Error initializing payment:', error.message);
    }
}
        async function handleSubmit(e) {
            e.preventDefault();
            setLoading(true);

            const {
                error
            } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    // Make sure to change this to your payment completion page
                    return_url: "{{ route('stripe.return', $order->id) }}",
                },
            });

            // This point will only be reached if there is an immediate error when
            // confirming the payment. Otherwise, your customer will be redirected to
            // your `return_url`. For some payment methods like iDEAL, your customer will
            // be redirected to an intermediate site first to authorize the payment, then
            // redirected to the `return_url`.
            if (error.type === "card_error" || error.type === "validation_error") {
                showMessage(error.message);
            } else {
                showMessage("An unexpected error occurred.");
            }

            setLoading(false);
        }
        
        // ------- UI helpers -------

        function showMessage(messageText) {
            const messageContainer = document.querySelector("#payment-message");

            messageContainer.style.display = "block";
            messageContainer.textContent = messageText;

            setTimeout(function() {
                messageContainer.style.display = "none";
                messageText.textContent = "";
            }, 4000);
        }

        // Show a spinner on payment submission
        function setLoading(isLoading) {
            if (isLoading) {
                // Disable the button and show a spinner
                document.querySelector("#submit").disabled = true;
                document.querySelector("#spinner").style.display = "inline";
                document.querySelector("#button-text").style.display = "none";
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#spinner").style.display = "none";
                document.querySelector("#button-text").style.display = "inline";
            }
        }
    </script>
</x-front-layout>