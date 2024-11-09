'use client';

import { loadStripe } from '@stripe/stripe-js';

const stripePromise = loadStripe(process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY!);

export async function redirectToCheckout(cartItems: any[]) {
    const stripe = await stripePromise;

    const response = await fetch('/api/create-checkout-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ cartItems }),
    });

    const { sessionId, error } = await response.json();

    if (error) {
        console.error('Error creating checkout session:', error);
        alert('Failed to create checkout session.');
        return;
    }

    const { error: stripeError } = await stripe!.redirectToCheckout({
        sessionId,
    });

    if (stripeError) {
        console.error('Stripe redirect error:', stripeError);
        alert('Failed to redirect to checkout.');
    }
}
