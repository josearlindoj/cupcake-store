// OpenCart.jsx
'use client';

import { ShoppingCartIcon } from '@heroicons/react/24/outline';
import clsx from 'clsx';
import { useEffect, useState } from 'react';
import {useCart} from '@/components/cart/cart-context';

// @ts-ignore
export default function OpenCart() {
    const [quantity, setQuantity] = useState(0);

    const { cart } = useCart();

    useEffect(() => {
        setQuantity(cart?.totalQuantity || 0);
    }, [cart]);

    return (
        <div className="relative flex h-11 w-11 items-center justify-center rounded-md border border-neutral-200 text-black transition-colors dark:border-neutral-700 dark:text-white">
            <ShoppingCartIcon
                className={clsx('h-4 transition-all ease-in-out hover:scale-110')}
            />

            {quantity > 0 && (
                <div className="absolute right-0 top-0 -mr-2 -mt-2 h-4 w-4 rounded bg-blue-600 text-[11px] font-medium text-white flex items-center justify-center">
                    {quantity}
                </div>
            )}
        </div>
    );
}
