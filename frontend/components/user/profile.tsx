'use client';

import React from "react";

interface UserProfileProps {
    user: {
        name: string;
        email: string;
    };
    onLogout: () => void;
}

export default function Profile({ user, onLogout }: UserProfileProps) {
    if (!user) {
        return null;
    }

    return (
        <div className="flex flex-col space-y-4">
            <div className="grid grid-cols-2 items-center">
                <span className="text-lg font-medium">Welcome, {user.name}</span>
                <a
                    onClick={onLogout}
                    className="cursor-pointer font-bold p-2 justify-self-end text-lg underline-offset-4 hover:text-black hover:underline md:inline-block md:text-sm dark:hover:text-neutral-300"
                >
                    Logout
                </a>
            </div>
            <input
                type="text"
                value={user.name}
                readOnly
                className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
            <input
                type="email"
                value={user.email}
                readOnly
                className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
        </div>
    );
}
