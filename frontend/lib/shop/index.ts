import {Cart, Menu, Page, Product} from "./types";
import axios from "axios";

const mockCart: Cart = {
    lines: [
        {
            id: "cart-item-1",
            quantity: 2,
            cost: {
                totalAmount: {
                    amount: "100.00",
                    currencyCode: "USD",
                },
            },
            merchandise: {
                id: "product-1-variant-1",
                title: "T-shirt - Blue",
                selectedOptions: [
                    {
                        name: "Color",
                        value: "Blue",
                    },
                    {
                        name: "Size",
                        value: "M",
                    },
                ],
                product: {
                    id: "product-1",
                    handle: "t-shirt",
                    title: "T-shirt",
                    featuredImage: {
                        src: "/img/products/t-shirt-blue.jpg",
                        altText: "Blue T-shirt",
                    },
                },
            },
        },
        {
            id: "cart-item-2",
            quantity: 1,
            cost: {
                totalAmount: {
                    amount: "150.00",
                    currencyCode: "USD",
                },
            },
            merchandise: {
                id: "product-2-variant-1",
                title: "Sneakers - White",
                selectedOptions: [
                    {
                        name: "Color",
                        value: "White",
                    },
                    {
                        name: "Size",
                        value: "9",
                    },
                ],
                product: {
                    id: "product-2",
                    handle: "sneakers",
                    title: "Sneakers",
                    featuredImage: {
                        src: "/img/products/sneakers-white.jpg",
                        altText: "White sneakers",
                    },
                },
            },
        },
    ],
};

// Define a mock product
const mockProduct: Product = {
    id: 'mock-id',
    title: 'Test Product',
    handle: 'test-product',
    availableForSale: true,
    description: '',
    descriptionHtml: `
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
    seo: {
        title: "Cupcake Store - Best Cupcakes Online",
        description: "Shop the best cupcakes online with a wide variety of flavors. Enjoy free shipping and high-quality, handcrafted cupcakes delivered to your door.",
    },
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
    variants: [
        {
            id: "variant-1",
            title: "T-shirt - Red / Medium",
            availableForSale: true,
            selectedOptions: [
                {
                    name: "Color",
                    value: "Red",
                },
                {
                    name: "Size",
                    value: "Medium",
                },
            ],
            price: {
                amount: "25.00",
                currencyCode: "USD",
            },
        },
        {
            id: "variant-2",
            title: "T-shirt - Blue / Large",
            availableForSale: false,
            selectedOptions: [
                {
                    name: "Color",
                    value: "Blue",
                },
                {
                    name: "Size",
                    value: "Large",
                },
            ],
            price: {
                amount: "27.00",
                currencyCode: "USD",
            },
        },
        {
            id: "variant-3",
            title: "T-shirt - Green / Small",
            availableForSale: true,
            selectedOptions: [
                {
                    name: "Color",
                    value: "Green",
                },
                {
                    name: "Size",
                    value: "Small",
                },
            ],
            price: {
                amount: "24.00",
                currencyCode: "USD",
            },
        },
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
    options: [
        {
            id: "option-1",
            name: "Color",
            values: ["Red", "Blue", "Green", "Black"],
        },
        {
            id: "option-2",
            name: "Size",
            values: ["Small", "Medium", "Large", "Extra Large"],
        }
    ]
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

export async function getCart(cartId: string | undefined): Promise<Cart | undefined> {
    if (!cartId) {
        return undefined;
    }

    return mockCart;
}

export async function addToCart(
    cartId: string,
    lines: { merchandiseId: string; quantity: number }[]
): Promise<Cart> {
    return mockCart;
}

export async function createCart(): Promise<Cart> {
    return mockCart;
}

export async function removeFromCart(cartId: string, lineIds: string[]): Promise<Cart> {
    return mockCart;
}

export async function updateCart(
    cartId: string,
    lines: { id: string; merchandiseId: string; quantity: number }[]
): Promise<Cart> {
    return mockCart;
}

export async function getMenu(handle: string): Promise<Menu[]> {
    // Mocked menu items
    const mockMenu: Menu[] = [
        {
            title: 'Home',
            path: '/',
        },
        {
            title: 'Shop',
            path: '/search',
        },
    ];

    return mockMenu;
}

export async function getPage(handle: string): Promise<Page> {
    const res = await shopifyFetch<ShopifyPageOperation>({
        query: getPageQuery,
        cache: 'no-store',
        variables: {handle}
    });

    return res.body.data.pageByHandle;
}

export async function getProduct(handle: string): Promise<Product | null> {
    try {
        const response = await axios.get(`${process.env.NEXT_PUBLIC_API_URL}/admin/products/${handle}`, {
            headers: {
                Accept: 'application/json',
            },
        });
        console.log(response.data)
        const productData = response.data;

        return {
            id: productData.id,
            title: productData.name,
            handle: productData.slug,
            description: productData.description,
            availableForSale: productData.availableForSale,
            skus: productData.skus,
            availableForSale: true,
            featuredImage: productData.featured_image_url
                ? { url: productData.featured_image_url, altText: productData.name }
                : undefined,
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
            images: productData.skus.flatMap((sku: any) => sku.images || []).map((img: any) => ({
                url: img.url,
                altText: img.altText || productData.name,
            })),
            tags: productData.tags || [],
        };
    } catch (error) {
        if (axios.isAxiosError(error)) {
            // Log specific error information from Axios
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