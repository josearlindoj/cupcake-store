export type Maybe<T> = T | null;

export type Connection<T> = {
    edges: Array<Edge<T>>;
};

export type Edge<T> = {
    node: T;
};

export type Cart = Omit<ShopifyCart, 'lines'> & {
    lines: CartItem[];
};

export type CartProduct = {
    id: string;
    handle: string;
    title: string;
    featuredImage: Image;
};

export type CartItem = {
    id: string | undefined;
    quantity: number;
    cost: {
        totalAmount: Money;
    };
    merchandise: {
        id: string;
        title: string;
        selectedOptions: {
            name: string;
            value: string;
        }[];
        product: CartProduct;
    };
};

export type Collection = ShopifyCollection & {
    path: string;
};

export type Image = {
    url: string;
    altText: string;
    width: number;
    height: number;
};

export type Menu = {
    title: string;
    path: string;
};

export type Money = {
    amount: string;
    currencyCode: string;
};

export type Page = {
    id: string;
    title: string;
    handle: string;
    body: string;
    bodySummary: string;
    seo?: SEO;
    createdAt: string;
    updatedAt: string;
};

export type Product = {
    id: string;
    title: string;
    description: string;
    availableForSale: boolean;
    handle: string;
    skus: SKU[];
    images: Image[];
    tags: string[];
    featuredImage: Image;
    priceRange: {
        maxVariantPrice: Money;
        minVariantPrice: Money;
    };
};

export type SKU = {
    id: number;
    code: string;
    price: number;
    attribute_options: Array<{
        id: number;
        value: string;
    }>;
};

export type ProductVariant = {
    id: string;
    title: string;
    availableForSale: boolean;
    attribute_options: {
        id: string;
        name: string;
        value: string;
        attribute_name: string;
    }[];
    price: Money;
};

export type SEO = {
    title: string;
    description: string;
};

export type ShopifyCart = {
    id: string | undefined;
    checkoutUrl: string;
    cost: {
        subtotalAmount: Money;
        totalAmount: Money;
        totalTaxAmount: Money;
    };
    lines: Connection<CartItem>;
    totalQuantity: number;
};

export type ShopifyCollection = {
    handle: string;
    title: string;
    description: string;
    seo: SEO;
    updatedAt: string;
};
