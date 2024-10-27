export function isTokenValid(): boolean {
    const expiresAt = localStorage.getItem("tokenExpiresAt");

    if (!expiresAt) return false;

    const now = new Date().getTime();
    const expirationTime = new Date(expiresAt).getTime();

    return now < expirationTime;
}

export function getAuthToken(): string | null {
    return localStorage.getItem("accessToken");
}