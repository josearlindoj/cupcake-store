'use client';

import clsx from 'clsx';
import {useProduct, useUpdateURL} from '@/components/product/product-context';
import {ProductOption} from '@/lib/shop/types';

export function VariantSelector({options = [], variants = []}: {
    options: ProductOption[];
    variants: any[];
}) {
    const {state, updateOption} = useProduct();
    const updateURL = useUpdateURL();

    // If there are no options or only one option group, return null
    const hasNoOptionsOrJustOneOption = variants.length < 2;

    if (hasNoOptionsOrJustOneOption) {
        return null;
    }

    return variants[0].attribute_options.map((attributeOptionGroup) => (
        <form key={attributeOptionGroup.variants.id}>
            <dl className="mb-8">
                <dt className="mb-4 text-sm uppercase tracking-wide">{attributeOptionGroup.variants.name}</dt>
                <dd className="flex flex-wrap gap-3">
                    {attributeOptionGroup.options.map((option) => {
                        const optionNameLowerCase = attributeOptionGroup.variants.name.toLowerCase();
                        const isActive = state[optionNameLowerCase] === option.value;

                        return (
                            <button
                                key={option.id}
                                onClick={(e) => {
                                    e.preventDefault();
                                    const newState = updateOption(optionNameLowerCase, option.value);
                                    updateURL(newState);
                                }}
                                aria-disabled={!option.availableForSale}
                                disabled={!option.availableForSale}
                                title={`${attributeOptionGroup.variants.name} ${option.value}${!option.availableForSale ? ' (Out of Stock)' : ''}`}
                                className={clsx(
                                    'flex min-w-[48px] items-center justify-center rounded-full border bg-neutral-100 px-2 py-1 text-sm dark:border-neutral-800 dark:bg-neutral-900',
                                    {
                                        'cursor-default ring-2 ring-blue-600': isActive,
                                        'ring-1 ring-transparent transition duration-300 ease-in-out hover:ring-blue-600':
                                            !isActive && option.availableForSale,
                                        'relative z-10 cursor-not-allowed overflow-hidden bg-neutral-100 text-neutral-500 ring-1 ring-neutral-300 before:absolute before:inset-x-0 before:-z-10 before:h-px before:-rotate-45 before:bg-neutral-300 before:transition-transform dark:bg-neutral-900 dark:text-neutral-400 dark:ring-neutral-700 before:dark:bg-neutral-700':
                                            !option.availableForSale
                                    }
                                )}
                            >
                                {option.value}
                            </button>
                        );
                    })}
                </dd>
            </dl>
        </form>
    ));
}
