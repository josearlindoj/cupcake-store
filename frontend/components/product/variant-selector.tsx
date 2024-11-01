'use client';

import clsx from 'clsx';
import { useProduct, useUpdateURL } from '@/components/product/product-context';
import { ProductOption, ProductVariant } from '@/lib/shop/types';

type Combination = {
    id: string;
    availableForSale: boolean;
    [key: string]: string | boolean;
};

export function VariantSelector({options = [],variants = []}: {
    options: ProductOption[];
    variants: ProductVariant[];
}) {
    const { state, updateOption } = useProduct();
    const updateURL = useUpdateURL();

    // const hasNoOptionsOrJustOneOption = !options?.length || (options.length === 1 && options[0]?.values?.length === 1);
    //
    // if (hasNoOptionsOrJustOneOption) {
    //     return null;
    // }

    // Build combination list for option availability checks
    const combinations: Combination[] = variants.map((variant) => ({
        id: variant.id,
        availableForSale: true,
        ...variant.attribute_options.reduce(
            (accumulator, option) => ({ ...accumulator, [option.value.toLowerCase()]: option.value }),
            {}
        )
    }));

    return options.map((option) => (
        <form key={option.id}>
            <dl className="mb-8">
                <dt className="mb-4 text-sm uppercase tracking-wide">{option.name}</dt>
                <dd className="flex flex-wrap gap-3">
                    {option.values.map((value) => {
                        const optionNameLowerCase = option.name.toLowerCase();

                        const optionParams = { ...state, [optionNameLowerCase]: value };

                        const filtered = Object.entries(optionParams).filter(([key, val]) =>
                            options.find(
                                (opt) => opt.name.toLowerCase() === key && opt.values.includes(val)
                            )
                        );
                        const isAvailableForSale = combinations.some((combination) =>
                            filtered.every(
                                ([key, val]) => combination[key] === val && combination.availableForSale
                            )
                        );

                        const isActive = state[optionNameLowerCase] === value;

                        return (
                            <button
                                onClick={(e) => {
                                    e.preventDefault();
                                    const newState = updateOption(optionNameLowerCase, value);
                                    updateURL(newState);
                                }}
                                key={value}
                                aria-disabled={!isAvailableForSale}
                                disabled={!isAvailableForSale}
                                title={`${option.name} ${value}${!isAvailableForSale ? ' (Out of Stock)' : ''}`}
                                className={clsx(
                                    'flex min-w-[48px] items-center justify-center rounded-full border bg-neutral-100 px-2 py-1 text-sm dark:border-neutral-800 dark:bg-neutral-900',
                                    {
                                        'cursor-default ring-2 ring-blue-600': isActive,
                                        'ring-1 ring-transparent transition duration-300 ease-in-out hover:ring-blue-600':
                                            !isActive && isAvailableForSale,
                                        'relative z-10 cursor-not-allowed overflow-hidden bg-neutral-100 text-neutral-500 ring-1 ring-neutral-300 before:absolute before:inset-x-0 before:-z-10 before:h-px before:-rotate-45 before:bg-neutral-300 before:transition-transform dark:bg-neutral-900 dark:text-neutral-400 dark:ring-neutral-700 before:dark:bg-neutral-700':
                                            !isAvailableForSale
                                    }
                                )}
                            >
                                {value}
                            </button>
                        );
                    })}
                </dd>
            </dl>
        </form>
    ));
}
