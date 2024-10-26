import { GridTileImage } from '@/components/grid/tile';
import Link from 'next/link';
import type { Product } from '@/lib/shop/types';

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

function ThreeItemGridItem({
    item,
    size,
    priority
}: {
    item: Product;
    size: 'full' | 'half';
    priority?: boolean;
}) {
    return (
        <div className={size === 'full' ? 'md:col-span-4 md:row-span-2' : 'md:col-span-2 md:row-span-1'}>
            <Link
                className="relative block aspect-square h-full w-full"
                href={`/product/${item.handle}`}
                prefetch={true}
            >
                <GridTileImage
                    src={item.featuredImage.url}
                    fill
                    sizes={
                        size === 'full' ? '(min-width: 768px) 66vw, 100vw' : '(min-width: 768px) 33vw, 100vw'
                    }
                    priority={priority}
                    alt={item.title}
                    label={{
                        position: size === 'full' ? 'center' : 'bottom',
                        title: item.title,
                        amount: item.priceRange.maxVariantPrice.amount,
                        currencyCode: item.priceRange.maxVariantPrice.currencyCode
                    }}
                />
            </Link>
        </div>
    );
}

export async function ThreeItemGrid() {
    // Replace API call with mock data for testing
    const homepageItems = mockProducts;

    if (!homepageItems[0] || !homepageItems[1] || !homepageItems[2]) return null;

    const [firstProduct, secondProduct, thirdProduct] = homepageItems;

    return (
        <section className="mx-auto grid max-w-screen-2xl gap-4 px-4 pb-4 md:grid-cols-6 md:grid-rows-2 lg:max-h-[calc(100vh-200px)]">
            <ThreeItemGridItem size="full" item={firstProduct} priority={true} />
            <ThreeItemGridItem size="half" item={secondProduct} priority={true} />
            <ThreeItemGridItem size="half" item={thirdProduct} />
        </section>
    );
}
