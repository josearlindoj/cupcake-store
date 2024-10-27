import { isTokenValid, getAuthToken } from "./auth";

export async function fetchWithAuth(url: string, options: RequestInit = {}) {
    if (!isTokenValid()) {
        throw new Error("Token expired. Please log in again.");
    }

    const token = getAuthToken();

    if (!token) {
        throw new Error("No token found. Please log in.");
    }

    const authOptions = {
        ...options,
        headers: {
            ...options.headers,
            Authorization: `Bearer ${token}`,
        },
    };

    const response = await fetch(url, authOptions);

    if (response.status === 401) {
        // Handle re-login or redirect to login page if token is invalid
        throw new Error("Unauthorized. Please log in again.");
    }

    return response;
}