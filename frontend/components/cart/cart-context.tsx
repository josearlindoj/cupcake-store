'use client';

import type { Cart, CartItem, Product, ProductVariant } from '@/lib/shop/types';
import React, {
    createContext,
    useContext,
    useMemo,
    useOptimistic,
    startTransition,
    useEffect,
} from 'react';
// @ts-ignore
import Cookies from 'js-cookie';

type UpdateType = 'plus' | 'minus' | 'delete';

type CartAction =
    | { type: 'UPDATE_ITEM'; payload: { merchandiseId: string; updateType: UpdateType } }
    | { type: 'ADD_ITEM'; payload: { variant: ProductVariant; product: Product } };

type CartContextType = {
    cart: Cart | undefined;
    updateCartItem: (merchandiseId: string, updateType: UpdateType) => void;
    addCartItem: (variant: ProductVariant, product: Product) => void;
};

const CartContext = createContext<CartContextType | undefined>(undefined);

function calculateItemCost(quantity: number, price: string): string {
    return (Number(price) * quantity).toString();
}

function updateCartItemFunction(item: CartItem, updateType: UpdateType): CartItem | null {
    if (updateType === 'delete') return null;

    const newQuantity = updateType === 'plus' ? item.quantity + 1 : item.quantity - 1;
    if (newQuantity === 0) return null;

    const singleItemAmount = Number(item.cost.totalAmount.amount) / item.quantity;
    const newTotalAmount = calculateItemCost(newQuantity, singleItemAmount.toString());

    return {
        ...item,
        quantity: newQuantity,
        cost: {
            ...item.cost,
            totalAmount: {
                ...item.cost.totalAmount,
                amount: newTotalAmount,
            },
        },
    };
}

function createOrUpdateCartItem(
    existingItem: CartItem | undefined,
    variant: any,
    product: any
): CartItem {
    const quantity = existingItem ? existingItem.quantity + 1 : 1;
    const price = variant.price;
    const totalAmount = calculateItemCost(quantity, price.toString());

    const selectedOptions = variant.attribute_options?.map((attributeOptionGroup: { variants: { name: any; }; options: any[]; }) => {
        const name = attributeOptionGroup.variants.name;
        const selectedOption = attributeOptionGroup.options.find(option => {
            return option;
        });
        return {
            name,
            value: selectedOption?.value || ''
        };
    }) || [];

    return {
        id: existingItem?.id,
        quantity,
        cost: {
            totalAmount: {
                amount: totalAmount,
                currencyCode: 'USD'
            }
        },
        merchandise: {
            id: variant.id,
            title: variant.code || '',
            selectedOptions,
            product: {
                id: product.id,
                handle: product.handle,
                title: product.title,
                featuredImage: product.images?.[0]
            }
        }
    };
}

function updateCartTotals(lines: CartItem[]): Pick<Cart, 'totalQuantity' | 'cost'> {
    const totalQuantity = lines.reduce((sum, item) => sum + item.quantity, 0);
    const totalAmount = lines.reduce((sum, item) => sum + Number(item.cost.totalAmount.amount), 0);
    const currencyCode = lines[0]?.cost.totalAmount.currencyCode ?? 'USD';

    return {
        totalQuantity,
        cost: {
            subtotalAmount: { amount: totalAmount.toString(), currencyCode },
            totalAmount: { amount: totalAmount.toString(), currencyCode },
            totalTaxAmount: { amount: '0', currencyCode },
        },
    };
}

function createEmptyCart(): Cart {
    return {
        id: undefined,
        checkoutUrl: '',
        totalQuantity: 0,
        lines: [],
        cost: {
            subtotalAmount: { amount: '0', currencyCode: 'USD' },
            totalAmount: { amount: '0', currencyCode: 'USD' },
            totalTaxAmount: { amount: '0', currencyCode: 'USD' },
        },
    };
}

function getCartFromCookies(): Cart {
    const cartData = Cookies.get('cart');
    if (cartData) {
        try {
            return JSON.parse(cartData);
        } catch (error) {
            console.error('Error parsing cart data from cookies:', error);
        }
    }
    return createEmptyCart();
}

function saveCartToCookies(cart: Cart) {
    try {
        const cartData = JSON.stringify(cart);
        Cookies.set('cart', cartData, { expires: 7 }); // Expires in 7 days
    } catch (error) {
        console.error('Error saving cart data to cookies:', error);
    }
}

function cartReducer(state: Cart | undefined, action: CartAction): Cart {
    const currentCart = state || createEmptyCart();

    switch (action.type) {
        case 'UPDATE_ITEM': {
            const { merchandiseId, updateType } = action.payload;
            const updatedLines = currentCart.lines
                .map((item) =>
                    item.merchandise.id === merchandiseId ? updateCartItemFunction(item, updateType) : item
                )
                .filter(Boolean) as CartItem[];

            if (updatedLines.length === 0) {
                return {
                    ...currentCart,
                    lines: [],
                    totalQuantity: 0,
                    cost: {
                        ...currentCart.cost,
                        totalAmount: { ...currentCart.cost.totalAmount, amount: '0' },
                    },
                };
            }

            return { ...currentCart, ...updateCartTotals(updatedLines), lines: updatedLines };
        }
        case 'ADD_ITEM': {
            const { variant, product } = action.payload;
            const existingItem = currentCart.lines.find((item) => item.merchandise.id === variant.id);
            const updatedItem = createOrUpdateCartItem(existingItem, variant, product);

            const updatedLines = existingItem
                ? currentCart.lines.map((item) => (item.merchandise.id === variant.id ? updatedItem : item))
                : [...currentCart.lines, updatedItem];

            return { ...currentCart, ...updateCartTotals(updatedLines), lines: updatedLines };
        }
        default:
            return currentCart;
    }
}

export function CartProvider({ children }: { children: React.ReactNode }) {
    const initialCart = getCartFromCookies();
    const [optimisticCart, updateOptimisticCart] = useOptimistic(initialCart, cartReducer);

    useEffect(() => {
        saveCartToCookies(optimisticCart);
    }, [optimisticCart]);

    const updateCartItem = (merchandiseId: string, updateType: UpdateType) => {
        startTransition(() => {
            updateOptimisticCart({ type: 'UPDATE_ITEM', payload: { merchandiseId, updateType } });
        });
    };

    const addCartItem = (variant: ProductVariant, product: Product) => {
        startTransition(() => {
            updateOptimisticCart({ type: 'ADD_ITEM', payload: { variant, product } });
        });
    };

    const value = useMemo(
        () => ({
            cart: optimisticCart,
            updateCartItem,
            addCartItem,
        }),
        [optimisticCart]
    );

    return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

export function useCart() {
    const context = useContext(CartContext);

    if (context === undefined) {
        throw new Error('useCart must be used within a CartProvider');
    }
    return context;
}
