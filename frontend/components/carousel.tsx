// import { getCollectionProducts } from 'lib/shopify';
import Link from 'next/link';
import { GridTileImage } from './grid/tile';
import { Product } from '@/lib/shop/types';

// Define a mock product
const mockProduct: Product = {
    id: 'mock-id',
    title: 'Test Product',
    handle: 'test-product',
    featuredImage: {
        url: '/img/products/product_1.png',
        altText: 'test',
        width: 1000,
        height: 1000
    },
    priceRange: {
        maxVariantPrice: {
            amount: '10.00',
            currencyCode: 'USD'
        },
        minVariantPrice: {
            amount: '5.00',
            currencyCode: 'USD'
        }
    }
};

// Define multiple mock products for testing
const mockProducts: Product[] = [
    {
        ...mockProduct,
        handle: 'test-product-1',
        title: 'Test Product 1'
    },
    {
        ...mockProduct,
        handle: 'test-product-2',
        title: 'Test Product 2'
    },
    {
        ...mockProduct,
        handle: 'test-product-3',
        title: 'Test Product 3'
    }
];

export async function Carousel() {
    const products = mockProducts;

    if (!products?.length) return null;

    const carouselProducts = [...products, ...products, ...products];

    return (
        <div className="w-full overflow-x-auto pb-6 pt-1">
            <ul className="flex animate-carousel gap-4">
                {carouselProducts.map((product, i) => (
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
                                    currencyCode: product.priceRange.maxVariantPrice.currencyCode
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
    );
}
