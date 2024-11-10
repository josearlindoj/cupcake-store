'use client';

import { PlusIcon } from '@heroicons/react/24/outline';
import clsx from 'clsx';
import { useProduct } from '@/components/product/product-context';
import { Product, SKU } from '@/lib/shop/types';
import { useCart } from '@/components/cart/cart-context';
import React from 'react';

interface SubmitButtonProps {
    availableForSale: boolean;
    selectedVariantId?: number;
    onClick: () => void;
}

function SubmitButton({ availableForSale, selectedVariantId, onClick, }: SubmitButtonProps) {
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
                'hover:opacity-90': true,
            })}
            onClick={onClick}
        >
            <div className="absolute left-0 ml-4">
                <PlusIcon className="h-5" />
            </div>
            Add To Cart
        </button>
    );
}

interface AddToCartProps {
    product: Product;
}

export function AddToCart({ product }: AddToCartProps) {
    const {skus = [], availableForSale} = product;
    const {addCartItem} = useCart();
    const {state} = useProduct();

    const variant = skus.find((sku: { attribute_options: any[]; }) =>
        sku.attribute_options.every((attributeOptionGroup: { options: { value: string; }[]; variants: { name: string; }; }) =>
            attributeOptionGroup.options.some((option: { value: string; }) =>
                option.value === state[attributeOptionGroup.variants.name.toLowerCase()]
            )
        )
    );

    const defaultVariantId = skus.length === 1 ? skus[0]?.id : undefined;
    const selectedVariantId = variant?.id || defaultVariantId;
    const finalVariant = skus.find((sku: SKU) => sku.id === selectedVariantId);

    const handleAddToCart = () => {
        if (finalVariant) {
            addCartItem(finalVariant, product);
        } else {
            alert('Please select product options before adding to cart.');
        }
    };

    return (
        <SubmitButton
            availableForSale={availableForSale}
            selectedVariantId={selectedVariantId}
            onClick={handleAddToCart}
        />
    );
}
