'use client';

import { useState } from "react";

interface SignupFormProps {
    onToggle: () => void;
}

export default function SignupForm({ onToggle }: SignupFormProps) {
    const [name, setName] = useState("");
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const [address, setAddress] = useState("");
    const [error, setError] = useState("");

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (password !== confirmPassword) {
            setError("Passwords do not match");
            return;
        }
        setError("");
        // Process signup logic here
        console.log({ name, email, password, address });
    };

    return (
        <div className="flex flex-col justify-between overflow-hidden p-1">
            <h2 className="font-bold">Create an account</h2>
            <form onSubmit={handleSubmit} className="flex flex-col space-y-4 my-4">
                <input
                    type="text"
                    placeholder="Name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                />
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
                <input
                    type="password"
                    placeholder="Confirm Password"
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                />
                <input
                    type="text"
                    placeholder="Address"
                    value={address}
                    onChange={(e) => setAddress(e.target.value)}
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                {error && <p className="text-red-500">{error}</p>}
            </form>

            <div className="flex flex-col items-center space-y-4 mt-4">
                <button
                    className="block w-full rounded-full bg-blue-600 p-3 text-center text-sm font-medium text-white opacity-90 hover:opacity-100"
                    type="submit"
                >
                    Sign Up
                </button>

                <p className="text-gray-400">
                    Already have an account?
                    <a
                        onClick={onToggle}
                        className="cursor-pointer font-bold p-2 text-lg underline-offset-4 hover:text-black hover:underline md:inline-block md:text-sm dark:hover:text-neutral-300"
                    >
                        Login
                    </a>
                </p>
            </div>
        </div>
    );
}
