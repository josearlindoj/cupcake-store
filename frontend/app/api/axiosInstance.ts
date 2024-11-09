// api/axiosInstance.ts
import axios from "axios";

// Create an Axios instance
const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
});

// Request interceptor to add token to headers
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("accessToken");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor to handle token expiration or unauthorized access
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 401) {
            // Handle unauthorized or expired token
            localStorage.removeItem("accessToken");
            localStorage.removeItem("tokenExpiresAt");
            localStorage.removeItem("user");
            window.location.href = "/login"; // Redirect to login page or show a logout prompt
        }
        return Promise.reject(error);
    }
);

export default api;
