import { CardElement, useStripe, useElements } from '@stripe/react-stripe-js';
import { useState } from 'react';

function CheckoutForm({ amount }) {
    const stripe = useStripe();
    const elements = useElements();

    const [errorMessage, setErrorMessage] = useState('');
    const [isProcessing, setIsProcessing] = useState(false);

    const handleSubmit = async (event) => {
        event.preventDefault();

        if (!stripe || !elements) return;

        setIsProcessing(true);
        setErrorMessage('');

        const response = await fetch('/api/payment_intents', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ amount }),
        });

        const { clientSecret, error: serverError } = await response.json();

        if (serverError) {
            setErrorMessage(serverError);
            setIsProcessing(false);
            return;
        }

        const { error: stripeError, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: elements.getElement(CardElement),
            },
        });

        if (stripeError) {
            setErrorMessage(stripeError.message);
            setIsProcessing(false);
        } else if (paymentIntent.status === 'succeeded') {
            alert('Payment successful!');
            setIsProcessing(false);
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            <CardElement options={{ hidePostalCode: true }} />
            {errorMessage && <div style={{ color: 'red' }}>{errorMessage}</div>}
            <button type="submit" disabled={isProcessing || !stripe}>
                {isProcessing ? 'Processing...' : `Pay $${(amount / 100).toFixed(2)}`}
            </button>
        </form>
    );
}

export default CheckoutForm;
