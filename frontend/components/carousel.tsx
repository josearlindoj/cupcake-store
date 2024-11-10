"use client";

import Link from "next/link";
import { GridTileImage } from "./grid/tile";
import { Product } from "@/lib/shop/types";
import { useEffect, useState } from "react";

type Catalog = {
    id: number;
    name: string;
    products: Array<{
        id: number;
        name: string;
        slug: string;
        skus: Array<{ price: number }>;
    }>;
};

const formatProducts = (productsData: any[]): {
    featuredImage: { altText: any; width: number; url: any; height: number };
    handle: any;
    id: any;
    title: any;
    priceRange: {
        maxVariantPrice: { amount: string; currencyCode: string };
        minVariantPrice: { amount: string; currencyCode: string }
    }
}[] => {
    return productsData.map((product) => ({
        id: product.id,
        title: product.name,
        handle: product.slug,
        featuredImage: {
            url: product.images[0]?.url || "/img/products/product_1.png",
            altText: product.name,
            width: 1000,
            height: 1000,
        },
        priceRange: {
            maxVariantPrice: {
                amount: String(Math.max(...product.skus.map((sku: { price: any; }) => sku.price))),
                currencyCode: "USD",
            },
            minVariantPrice: {
                amount: String(Math.min(...product.skus.map((sku: { price: any; }) => sku.price))),
                currencyCode: "USD",
            },
        },
    }));
};

export function Carousel() {
    const [catalogs, setCatalogs] = useState<Catalog[]>([]);
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        async function fetchCatalogs() {
            try {
                const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/admin/catalogs`);
                if (!response.ok) throw new Error("Failed to fetch catalogs");

                const catalogsData = await response.json();
                setCatalogs(catalogsData);
            } catch (err) {
                setError((err as Error).message);
            } finally {
                setLoading(false);
            }
        }

        fetchCatalogs();
    }, []);

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error: {error}</p>;

    return (
        <div className="w-full overflow-x-auto pb-6 pt-1">
            {catalogs.map((catalog) => (
                <div key={catalog.id} className="mb-8">
                    <h2 className="text-2xl font-bold mb-4 ml-5">{catalog.name}</h2>
                    <ul className="flex gap-4">
                        {formatProducts(catalog.products).map((product, i) => (
                            <li
                                key={`${product.handle}${i}`}
                                className="relative aspect-square h-[30vh] max-h-[275px] w-2/3 max-w-[475px] flex-none md:w-1/3"
                            >
                                <Link href={`/product/${product.handle}`} className="relative h-full w-full">
                                    <GridTileImage
                                        alt={product.title}
                                        label={{
                                            title: product.title,
                                            amount: product.priceRange.maxVariantPrice.amount,
                                            currencyCode: product.priceRange.maxVariantPrice.currencyCode,
                                        }}
                                        src={product.featuredImage?.url}
                                        fill
                                        sizes="(min-width: 1024px) 25vw, (min-width: 768px) 33vw, 50vw"
                                    />
                                </Link>
                            </li>
                        ))}
                    </ul>
                </div>
            ))}
        </div>
    );
}
