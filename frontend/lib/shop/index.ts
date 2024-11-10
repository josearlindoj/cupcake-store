import {Menu, Product, Image} from '@/lib/shop/types';
import axios from "axios";

const mockProduct: {
    availableForSale: boolean;
    images: ({ altText: string; width: number; url: string; height: number })[];
    featuredImage: { altText: string; width: number; url: string; height: number };
    description: string;
    handle: string;
    id: string;
    title: string;
    priceRange: {
        maxVariantPrice: { amount: string; currencyCode: string };
        minVariantPrice: { amount: string; currencyCode: string }
    };
    tags: string[]
} = {
    id: 'mock-id',
    title: 'Test Product',
    handle: 'test-product',
    availableForSale: true,
    description: `
        <div>
            <h3>Delicious Cupcakes</h3>
            <p>Our cupcakes are made from the finest ingredients, offering a delightful treat for any occasion. Choose from a variety of flavors, including:</p>
            <p>Order today and enjoy free shipping on orders over $50!</p>
        </div>
    `,
    featuredImage: {
        url: '/img/products/product_1.png',
        altText: 'test',
        width: 1000,
        height: 1000
    },
    tags: [
        "New Arrival",
        "Best Seller",
        "Limited Edition",
        "Discount",
        "Organic",
        "Summer Collection",
        "Free Shipping",
    ],
    images: [
        {
            url: '/img/products/product_1.png',
            altText: 'test',
            width: 1000,
            height: 1000
        },
        {
            url: '/img/products/product_2.png',
            altText: 'test',
            width: 1000,
            height: 1000
        }
    ],
    priceRange: {
        maxVariantPrice: {
            amount: '10.00',
            currencyCode: 'USD'
        },
        minVariantPrice: {
            amount: '5.00',
            currencyCode: 'USD'
        }
    },
};

// Define multiple mock products for testing
const mockProducts: Product[] = [
    {
        ...mockProduct,
        handle: 'test-product-1',
        title: 'Test Product 1',
        skus: []
    },
    {
        ...mockProduct,
        handle: 'test-product-2',
        title: 'Test Product 2',
        skus: []
    },
    {
        ...mockProduct,
        handle: 'test-product-3',
        title: 'Test Product 3',
        skus: []
    }
];

export async function getMenu(handle: string): Promise<Menu[]> {
    return [
        {
            title: 'Home',
            path: '/',
        },
        {
            title: 'Shop',
            path: '/products',
        },
    ];
}

export async function getProduct(handle: string): Promise<Product | null> {
    try {
        const response = await axios.get(`${process.env.NEXT_PUBLIC_API_URL}/admin/products/${handle}`, {
            headers: {
                Accept: 'application/json',
            },
        });
        const productData = response.data;
        const featuredImage: Image = productData.featured_image_url
            ? {
                url: productData.featured_image_url,
                altText: productData.name,
                width: 1000,
                height: 1000,
            }
            : {
                url: "default-image-url.jpg",
                altText: "Default Image",
                width: 1000,
                height: 1000,
            };

        return {
            id: productData.id,
            title: productData.name,
            handle: productData.slug,
            description: productData.description,
            skus: productData.skus,
            availableForSale: true,
            featuredImage,
            priceRange: {
                minVariantPrice: {
                    amount: productData.skus.length ? String(Math.min(...productData.skus.map((sku: any) => sku.price))) : '0.00',
                    currencyCode: 'USD',
                },
                maxVariantPrice: {
                    amount: productData.skus.length ? String(Math.max(...productData.skus.map((sku: any) => sku.price))) : '0.00',
                    currencyCode: 'USD',
                },
            },
            images: productData.images.map((img: any) => ({
                url: img.url,
                altText: 'Cupcake',
                width: 1000,
                height: 1000,
            })),
            tags: productData.tags || [],
        };
    } catch (error) {
        if (axios.isAxiosError(error)) {
            console.error('Error fetching product:', {
                message: error.message,
                response: error.response?.data,
                status: error.response?.status,
                headers: error.response?.headers,
            });
        } else {
            console.error('Unexpected error:', error);
        }
        return null;
    }
}

export async function getProductRecommendations(productId: string): Promise<Product[]> {
    return mockProducts;
}

export const formatProducts = (productsData: any[]): Product[] => {
    return productsData.map((product) => ({
        id: product.id,
        title: product.name,
        handle: product.slug,
        description: product.description,
        skus: product.skus,
        availableForSale: true,
        featuredImage: {
            url: product.images[0]?.url || '/img/products/product_1.png',
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
        images: product.images.map((img: any) => ({
            url: img.url,
            altText: 'Cupcake',
            width: 1000,
            height: 1000,
        })),
        tags: product.tags || [],
    }));
};

export async function getProducts(): Promise<Product[] | null> {
    try {
        const response = await axios.get(`${process.env.NEXT_PUBLIC_API_URL}/admin/products`, {
            headers: {
                Accept: 'application/json',
            },
        });

        const productData = response.data;

        return formatProducts(productData);

    } catch (error) {
        if (axios.isAxiosError(error)) {
            console.error('Error fetching product:', {
                message: error.message,
                response: error.response?.data,
                status: error.response?.status,
                headers: error.response?.headers,
            });
        } else {
            console.error('Unexpected error:', error);
        }
        return null;
    }
}