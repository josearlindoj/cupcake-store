import Grid from '@/components/grid';
import ProductGridItems from '@/components/layout/product-grid-items';
import {getProducts} from '@/lib/shop';

export const metadata = {
    title: 'Search',
    description: 'Search for products in the store.'
};

export default async function ProductPage() {
    const products = await getProducts();
    const resultsText = products.length > 1 ? 'results' : 'result';

    return (
        <>
            <p className="mb-4">
                {products.length === 0
                    ? 'There are no products that match '
                    : `Showing ${products.length} ${resultsText} for `}
            </p>
            {products.length > 0 ? (
                <Grid className="grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                    <ProductGridItems products={products}/>
                </Grid>
            ) : null}
        </>
    );
}