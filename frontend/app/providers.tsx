'use client';

import { ReactNode } from 'react';
import { CartProvider } from '@/components/cart/cart-context';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

const stripePromise = loadStripe(process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY!);

export default function Providers({ children }: { children: ReactNode }) {
    return (
        <Elements stripe={stripePromise}>
            <CartProvider>
                {children}
            </CartProvider>
        </Elements>
    );
}
