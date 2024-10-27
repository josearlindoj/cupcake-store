'use client';

import {useState} from "react";
import LoadingIcon from "@/components/icons/loading";

interface SignupFormProps {
    onToggle: () => void;
}

export default function SignupForm({onToggle}: SignupFormProps) {
    const [name, setName] = useState("");
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const [errors, setErrors] = useState<{ [key: string]: string[] }>({});
    const [successMessage, setSuccessMessage] = useState("");
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        if (password !== confirmPassword) {
            setErrors({password: ["Passwords do not match"]});
            return;
        }

        setLoading(true);
        setErrors({});
        setSuccessMessage("");

        try {
            const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/admin/register`, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    name,
                    email,
                    password,
                    password_confirmation: confirmPassword,
                }),
            });

            if (response.ok) {
                const data = await response.json();
                setSuccessMessage(data.message);
                setErrors({});

                setName("");
                setEmail("");
                setPassword("");
                setConfirmPassword("");
            } else if (response.status === 422) {
                const errorData = await response.json();
                setErrors(errorData.errors || {});
            } else {
                throw new Error("An unexpected error occurred");
            }
        } catch (error) {
            console.error("Error:", error);
            setErrors({general: ["An unexpected error occurred. Please try again later."]});
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="flex flex-col justify-between overflow-hidden p-1">
            <h2 className="font-bold">Sign Up</h2>
            <form onSubmit={handleSubmit} className="flex flex-col space-y-4 my-4">
                <input
                    type="text"
                    placeholder="Name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                />
                {errors.name && <p className="text-red-500">{errors.name[0]}</p>}

                <input
                    type="email"
                    placeholder="E-mail"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                />
                {errors.email && <p className="text-red-500">{errors.email[0]}</p>}

                <input
                    type="password"
                    placeholder="Password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                />
                {errors.password && <p className="text-red-500">{errors.password[0]}</p>}

                <input
                    type="password"
                    placeholder="Confirm Password"
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                />
                {errors.confirmPassword && <p className="text-red-500">{errors.confirmPassword[0]}</p>}

                {errors.general && <p className="text-red-500">{errors.general[0]}</p>}
                {successMessage && <p className="text-green-500">{successMessage}</p>}

                <button
                    className="block w-full rounded-full bg-blue-600 p-3 text-center text-sm font-medium text-white opacity-90 hover:opacity-100"
                    type="submit"
                    disabled={loading}
                >
                    {loading ? (
                        <span className="flex items-center justify-center">
                            <LoadingIcon/>
                            Loading...
                        </span>
                    ) : (
                        "Sign Up"
                    )}
                </button>
            </form>

            <div className="flex flex-col items-center space-y-4 mt-4">
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
