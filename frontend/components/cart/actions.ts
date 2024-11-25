'use client';

import {loadStripe} from '@stripe/stripe-js';

const stripePromise = loadStripe(process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY!);

export async function redirectToCheckout(cartItems: any[], deliveryMethod: 'delivery' | 'store') {
    const stripe = await stripePromise;

    // const updatedCartItems = [...cartItems];

    if (deliveryMethod === 'delivery') {
        cartItems.push({
            quantity: 1,
            cost: {
                totalAmount: {
                    amount: '5',
                    currencyCode: 'USD',
                },
            },
            merchandise: {
                id: 'delivery-fee',
                title: 'Delivery Fee',
                selectedOptions: [],
                product: {
                    id: 'delivery-fee',
                    handle: 'delivery-fee',
                    title: 'Delivery Fee',
                    featuredImage: {
                        url: 'https://w7.pngwing.com/pngs/702/495/png-transparent-doorstep-delivery-computer-icons-others-miscellaneous-silhouette-area.png',
                        altText: 'Delivery Fee',
                        width: 100,
                        height: 100,
                    },
                },
            },
            price: null,
        });
    }

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
