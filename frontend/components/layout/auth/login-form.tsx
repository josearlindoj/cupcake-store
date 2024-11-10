'use client';

import { useState } from "react";
import LoadingIcon from "@/components/icons/loading";

interface LoginFormProps {
    onToggleAction: () => void;
    onSuccessAction: (userData: { name: string; email: string; }) => void;
}

export default function LoginForm({ onToggleAction, onSuccessAction }: LoginFormProps) {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);
    const [rememberMe, setRememberMe] = useState(false);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        setError("");

        try {
            const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/admin/login`, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    email,
                    password,
                }),
            });

            if (response.ok) {
                const data = await response.json();
                const { access_token, expires_at, user } = data.data;

                const storage = rememberMe ? localStorage : sessionStorage;
                storage.setItem("accessToken", access_token);
                storage.setItem("tokenExpiresAt", expires_at);
                storage.setItem("user", JSON.stringify(user));

                if (onSuccessAction) onSuccessAction(user);
            } else if (response.status === 401) {
                setError("Invalid login credentials.");
            } else {
                throw new Error("An unexpected error occurred.");
            }
        } catch (error) {
            console.error("Error:", error);
            setError("An unexpected error occurred. Please try again later.");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="flex flex-col justify-between overflow-hidden p-1">
            <h2 className="font-bold">Login to your account</h2>
            <form onSubmit={handleSubmit} className="flex flex-col space-y-4 my-4">
                <input
                    type="email"
                    placeholder="E-mail"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                />
                <input
                    type="password"
                    placeholder="Password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                />

                {error && <p className="text-red-500">{error}</p>}

                <button
                    className="block w-full rounded-full bg-blue-600 p-3 text-center text-sm font-medium text-white opacity-90 hover:opacity-100"
                    type="submit"
                    disabled={loading}
                >
                    {loading ? (
                        <span className="flex items-center justify-center">
                            <LoadingIcon />
                            Loading...
                        </span>
                    ) : (
                        "Login"
                    )}
                </button>
            </form>

            <div className="flex flex-col items-center space-y-4">
                <div className="flex items-center justify-between w-full">
                    {/*<label className="flex items-center space-x-2 text-gray-300">*/}
                    {/*    <input*/}
                    {/*        type="checkbox"*/}
                    {/*        checked={rememberMe}*/}
                    {/*        onChange={() => setRememberMe(!rememberMe)}*/}
                    {/*        className="text-indigo-500 border-gray-600 focus:ring-0 focus:ring-offset-0"*/}
                    {/*    />*/}
                    {/*    <span>Remember me</span>*/}
                    {/*</label>*/}
                    {/*<a href="#"*/}
                    {/*   className="block p-2 text-lg underline-offset-4 hover:text-black hover:underline md:inline-block md:text-sm dark:hover:text-neutral-300">Forgot*/}
                    {/*    password?</a>*/}
                </div>

                <p className="text-gray-400">
                    Don’t have an account yet?
                    <a
                        onClick={onToggleAction}
                        className="cursor-pointer font-bold p-2 text-lg underline-offset-4 hover:text-black hover:underline md:inline-block md:text-sm dark:hover:text-neutral-300"
                    >
                        Sign up
                    </a>
                </p>
            </div>
        </div>
    );
}
