'use client';

import { PlusIcon } from '@heroicons/react/24/outline';
import clsx from 'clsx';
import { addItem } from '@/components/cart/actions';
import { useProduct } from '@/components/product/product-context';
import { Product, SKU } from '@/lib/shop/types';
import { useActionState } from 'react';
import { useCart } from '@/components/cart/cart-context';

function SubmitButton({
                          availableForSale,
                          selectedVariantId
                      }: {
    availableForSale: boolean;
    selectedVariantId: string | undefined;
}) {
    const buttonClasses =
        'relative flex w-full items-center justify-center rounded-full bg-blue-600 p-4 tracking-wide text-white';
    const disabledClasses = 'cursor-not-allowed opacity-60 hover:opacity-60';

    if (!availableForSale) {
        return (
            <button disabled className={clsx(buttonClasses, disabledClasses)}>
                Out Of Stock
            </button>
        );
    }

    if (!selectedVariantId) {
        return (
            <button
                aria-label="Please select an option"
                disabled
                className={clsx(buttonClasses, disabledClasses)}
            >
                <div className="absolute left-0 ml-4">
                    <PlusIcon className="h-5" />
                </div>
                Add To Cart
            </button>
        );
    }

    return (
        <button
            aria-label="Add to cart"
            className={clsx(buttonClasses, {
                'hover:opacity-90': true
            })}
        >
            <div className="absolute left-0 ml-4">
                <PlusIcon className="h-5" />
            </div>
            Add To Cart
        </button>
    );
}

export function AddToCart({ product }: { product: Product }) {
    const { skus = [], availableForSale } = product;
    const { addCartItem } = useCart();
    const { state } = useProduct();
    const [message, formAction] = useActionState(addItem, null);

    // Determine the selected SKU (variant) based on user options
    const variant = skus.find((sku: SKU) =>
        sku.attributeOptions?.every((option) => option.value === state?.[option.name.toLowerCase()])
    );

    // Handle the case for a default SKU if only one SKU is present
    const defaultVariantId = skus.length === 1 ? skus[0]?.id : undefined;
    const selectedVariantId = variant?.id || defaultVariantId;
    const actionWithVariant = selectedVariantId ? formAction.bind(null, selectedVariantId) : () => {};
    const finalVariant = skus.find((sku) => sku.id === selectedVariantId);

    return (
        <form
            action={async () => {
                if (finalVariant) {
                    addCartItem(finalVariant, product);
                    await actionWithVariant();
                }
            }}
        >
            <SubmitButton availableForSale={availableForSale} selectedVariantId={selectedVariantId} />
            <p aria-live="polite" className="sr-only" role="status">
                {message}
            </p>
        </form>
    );
}
