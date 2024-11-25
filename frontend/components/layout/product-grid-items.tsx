import Grid from '@/components/grid';
import { GridTileImage } from '@/components/grid/tile';
import { Product } from '@/lib/shop/types';
import Link from 'next/link';
import {formatProducts} from '@/lib/shop';

export default function ProductGridItems({ products }: { products: Product[] }) {
    return (
        <>
            {products.map((product) => (
                <Grid.Item key={product.handle} className="animate-fadeIn">
                    <Link
                        className="relative inline-block h-full w-full"
                        href={`/product/${product.handle}`}
                        prefetch={true}
                    >
                        <GridTileImage
                            src={product.featuredImage?.url  || '/img/products/product_1.png'}
                            fill
                            sizes="(min-width: 768px) 33vw, (min-width: 640px) 50vw, 100vw"
                            alt={product.title}
                            priority={true}
                            label={{
                                title: product.title,
                                amount: product.priceRange.maxVariantPrice.amount,
                                currencyCode: product.priceRange.maxVariantPrice.currencyCode,
                            }}
                        />
                    </Link>
                </Grid.Item>
            ))}
        </>
    );
}