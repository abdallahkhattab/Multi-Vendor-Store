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
            background-color: #0056b3;
        }
        /* Disable state for the button */
        #submit:disabled {
            background-color: #ccc;
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
            display: none !important;
        }
        /* Full-page overlay for loading */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .loading-text {
            color: white;
            font-size: 20px;
            font-weight: bold;
            margin-left: 10px;
        }
    </style>
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <div id="payment-status" class="hidden alert alert-info"></div>
                    <!-- Loading Overlay -->
                    <div id="loading-overlay" class="loading-overlay hidden">
                        <div class="spinner"></div>
                        <span class="loading-text">Processing Payment...</span>
                    </div>
                    <!-- Display a payment form -->
                    <form id="payment-form">
                        <div id="payment-element">
                            <!-- Stripe.js injects the Payment Element -->
                        </div>
                        <button id="submit" aria-live="polite">
                            <div class="spinner hidden" id="spinner" aria-hidden="true"></div>
                            <span id="button-text">Pay now</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ config('services.stripe.publishable_key') }}");
        let elements;

        initialize();
        document.querySelector("#payment-form").addEventListener("submit", handleSubmit);

        async function initialize() {
            try {
                const response = await fetch("{{ route('stripe.paymentIntent.create', $order->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({}),
                });

                if (!response.ok) {
                    let errorMessage = "An unexpected error occurred.";
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.error || errorMessage;
                    } catch (parseError) {
                        errorMessage = response.statusText || errorMessage;
                    }
                    console.error(errorMessage);
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

            const { error } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: "{{ route('stripe.return', $order->id) }}",
                },
            });

            if (error) {
                if (error.type === "card_error" || error.type === "validation_error") {
                    showMessage(error.message);
                } else {
                    showMessage("An unexpected error occurred.");
                }
            } else {
                showMessage("Payment successful!");
            }

            setLoading(false);
        }

        function showMessage(messageText) {
            const messageContainer = document.querySelector("#payment-status");
            messageContainer.style.display = "block";
            messageContainer.textContent = messageText;

            setTimeout(() => {
                messageContainer.style.display = "none";
                messageContainer.textContent = "";
            }, 6000);
        }

        function setLoading(isLoading) {
            const submitButton = document.querySelector("#submit");
            const spinner = document.querySelector("#spinner");
            const buttonText = document.querySelector("#button-text");
            const loadingOverlay = document.querySelector("#loading-overlay");

            if (isLoading) {
                submitButton.disabled = true;
                submitButton.setAttribute("aria-busy", "true");
                spinner.style.display = "inline";
                buttonText.style.display = "none";
                loadingOverlay.classList.remove("hidden");
            } else {
                submitButton.disabled = false;
                submitButton.removeAttribute("aria-busy");
                spinner.style.display = "none";
                buttonText.style.display = "inline";
                loadingOverlay.classList.add("hidden");
            }
        }
    </script>
</x-front-layout>