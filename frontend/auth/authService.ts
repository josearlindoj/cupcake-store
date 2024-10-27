// auth/authService.ts
import api from "@/api/axiosInstance";

// Login function
export async function login(email: string, password: string) {
    try {
        const response = await api.post("/admin/login", { email, password });
        const { access_token, expires_at, user } = response.data.data;

        // Save token and expiration date to localStorage
        localStorage.setItem("accessToken", access_token);
        localStorage.setItem("tokenExpiresAt", expires_at);
        localStorage.setItem("user", JSON.stringify(user));

        return user;
    } catch (error) {
        console.error("Login error:", error);
        throw error;
    }
}

// Logout function
export function logout() {
    localStorage.removeItem("accessToken");
    localStorage.removeItem("tokenExpiresAt");
    localStorage.removeItem("user");
}

// Check if the user is authenticated
export function isAuthenticated(): boolean {
    const token = localStorage.getItem("accessToken");
    const expiresAt = localStorage.getItem("tokenExpiresAt");

    if (token && expiresAt) {
        const isExpired = new Date() > new Date(expiresAt);
        if (isExpired) {
            logout();
            return false;
        }
        return true;
    }
    return false;
}
