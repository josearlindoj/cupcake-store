"use client"

import {GridTileImage} from '@/components/grid/tile';
import Link from 'next/link';
import {useEffect, useState} from 'react';
import LoadingDots from "@/components/loading-dots";

interface Product {
    id: number;
    name: string;
    slug: string;
    featuredImage?: {
        url: string;
        altText: string;
        width: number;
        height: number;
    };
    priceRange: {
        maxVariantPrice: {
            amount: string;
            currencyCode: string;
        };
        minVariantPrice: {
            amount: string;
            currencyCode: string;
        };
    };
}

// Helper function to format the API data
const formatProducts = (productsData: any[]): Product[] => {
    return productsData.map((product) => ({
        id: product.id,
        name: product.name,
        slug: product.slug,
        featuredImage: {
            url: '/img/products/product_1.png',
            altText: product.name,
            width: 1000,
            height: 1000,
        },
        priceRange: {
            maxVariantPrice: {
                amount: String(Math.max(...product.skus.map((sku: any) => sku.price))),
                currencyCode: 'USD',
            },
            minVariantPrice: {
                amount: String(Math.min(...product.skus.map((sku: any) => sku.price))),
                currencyCode: 'USD',
            },
        },
    }));
};

function ThreeItemGridItem({item, size, priority,}: { item: Product; size: 'full' | 'half'; priority?: boolean; }) {
    const image = item.featuredImage?.url || '/img/products/product_1.png';
    return (
        <div className={size === 'full' ? 'md:col-span-4 md:row-span-2' : 'md:col-span-2 md:row-span-1'}>
            <Link
                className="relative block aspect-square h-full w-full"
                href={`/product/${item.slug}`}
                prefetch={true}
            >
                <GridTileImage
                    src={image}
                    fill
                    sizes={size === 'full' ? '(min-width: 768px) 66vw, 100vw' : '(min-width: 768px) 33vw, 100vw'}
                    priority={priority}
                    alt={item.name}
                    label={{
                        position: size === 'full' ? 'center' : 'bottom',
                        title: item.name,
                        amount: item.priceRange.maxVariantPrice.amount,
                        currencyCode: item.priceRange.maxVariantPrice.currencyCode,
                    }}
                />
            </Link>
        </div>
    );
}

export function ThreeItemGrid() {
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        async function fetchProducts() {
            try {
                const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/admin/products`);
                if (!response.ok) throw new Error('Failed to fetch products');

                const productsData = await response.json();
                setProducts(formatProducts(productsData));
            } catch (err) {
                // @ts-ignore
                setError(err.message);
            } finally {
                setLoading(false);
            }
        }

        fetchProducts();
    }, []);

    if (loading) return <div className="mx-2 inline-flex items-center"><LoadingDots className="bg-white"/></div>;
    if (error) return <div>Error: {error}</div>;
    if (products.length < 3) return <div>Not enough products to display.</div>;

    const [firstProduct, secondProduct, thirdProduct] = products;

    return (
        <section
            className="mx-auto grid max-w-screen-2xl gap-4 px-4 pb-4 md:grid-cols-6 md:grid-rows-2 lg:max-h-[calc(100vh-200px)]">
            <ThreeItemGridItem size="full" item={firstProduct} priority={true}/>
            <ThreeItemGridItem size="half" item={secondProduct} priority={true}/>
            <ThreeItemGridItem size="half" item={thirdProduct}/>
        </section>
    );
}
