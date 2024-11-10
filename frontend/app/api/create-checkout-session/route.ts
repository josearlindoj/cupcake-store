// app/api/create-checkout-session/route.ts
import { NextResponse } from 'next/server';
import Stripe from 'stripe';

const stripe = new Stripe(process.env.STRIPE_SECRET_KEY!, {
    apiVersion: '2024-10-28.acacia',
});

export async function POST(request: Request) {
    try {
        const { cartItems } = await request.json();

        if (!cartItems || !Array.isArray(cartItems) || cartItems.length === 0) {
            return NextResponse.json({ error: 'Cart is empty or invalid.' }, { status: 400 });
        }

        const lineItems = cartItems.map((item: any) => {
            const productTitle = item.merchandise.product.title || 'Product';
            const imageUrl = item.merchandise.product.featuredImage?.url;
            const description = item.merchandise.product.description;
            const quantity = item.quantity || 1;

            const totalAmount = Number(item.cost.totalAmount.amount);
            if (!totalAmount || isNaN(totalAmount)) {
                throw new Error('Invalid total amount for item.');
            }
            const unitAmount = Math.round((totalAmount / quantity) * 100); // Ensure proper parentheses

            const productData: any = {
                name: productTitle,
            };

            if (imageUrl) {
                productData.images = [imageUrl];
            }

            if (description) {
                productData.description = description;
            }

            return {
                price_data: {
                    currency: 'usd',
                    product_data: productData,
                    unit_amount: unitAmount,
                },
                quantity: quantity,
            };
        });

        const session = await stripe.checkout.sessions.create({
            payment_method_types: ['card'],
            line_items: lineItems,
            mode: 'payment',
            success_url: `${process.env.NEXT_PUBLIC_BASE_URL}/success?session_id={CHECKOUT_SESSION_ID}`,
            cancel_url: `${process.env.NEXT_PUBLIC_BASE_URL}/cancel`,
        });

        return NextResponse.json({ sessionId: session.id });
    } catch (error: any) {
        console.error('Error creating checkout session:', error);
        return NextResponse.json({ error: error.message }, { status: 500 });
    }
}
