// app/success/page.tsx
'use client';

import { useEffect } from 'react';
import { useCart } from '@/components/cart/cart-context';

export default function SuccessPage() {
    const { clearCart } = useCart();

    useEffect(() => {
        clearCart();
    }, []);

    return (
        <div>
            <h1>Payment Successful!</h1>
            <p>Thank you for your purchase.</p>
        </div>
    );
}
