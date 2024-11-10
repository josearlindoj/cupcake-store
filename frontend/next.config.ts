import type { NextConfig } from "next";

const nextConfig: NextConfig = {
    images: {
        domains: ['localhost'],
    },
    async headers() {
        return [
            {
                source: '/:path*',
                headers: [
                    { key: 'Access-Control-Allow-Origin', value: 'https://cupcake-store-a1d66243d848.herokuapp.com' },
                    { key: 'Access-Control-Allow-Methods', value: 'GET, POST, PUT, DELETE, OPTIONS' },
                    { key: 'Access-Control-Allow-Headers', value: 'Content-Type, Authorization' },
                    { key: 'Access-Control-Allow-Credentials', value: 'true' },
                ],
            },
        ];
    },
};

export default nextConfig;
