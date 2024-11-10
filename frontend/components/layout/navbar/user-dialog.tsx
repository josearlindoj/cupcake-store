'use client';

import { UserIcon } from "@heroicons/react/24/outline";
import { Dialog, Transition } from '@headlessui/react';
import clsx from "clsx";
import { Fragment, useState, useEffect } from "react";
import CloseCart from "@/components/cart/close-cart";
import LoginForm from "@/components/layout/auth/login-form";
import SignupForm from "@/components/layout/auth/signup-form";
import UserProfile from "@/components/user/profile";

export default function UserDialog() {
    const [isOpen, setIsOpen] = useState(false);
    const [showSignUp, setShowSignUp] = useState(false);
    const [isAuthenticated, setIsAuthenticated] = useState(false);
    const [user, setUser] = useState<{ name: string; email: string } | null>(null);

    useEffect(() => {
        const token = localStorage.getItem("accessToken") || sessionStorage.getItem("accessToken");
        const expiresAt = localStorage.getItem("tokenExpiresAt") || sessionStorage.getItem("tokenExpiresAt");

        if (token && expiresAt && new Date() < new Date(expiresAt)) {
            const storedUser = JSON.parse(localStorage.getItem("user") || sessionStorage.getItem("user") || "{}");
            setIsAuthenticated(true);
            setUser(storedUser);
        } else {
            clearAuthData();
        }
    }, []);

    const openModel = () => setIsOpen(true);
    const closeModel = () => setIsOpen(false);
    const toggleForm = () => setShowSignUp((prev) => !prev);

    const clearAuthData = () => {
        localStorage.removeItem("accessToken");
        localStorage.removeItem("tokenExpiresAt");
        localStorage.removeItem("user");
        sessionStorage.removeItem("accessToken");
        sessionStorage.removeItem("tokenExpiresAt");
        sessionStorage.removeItem("user");
        setIsAuthenticated(false);
        setUser(null);
    };

    const handleLoginSuccess = (userData: { name: string; email: string }) => {
        setIsAuthenticated(true);
        setUser(userData);
        closeModel();
    };

    const handleLogout = async () => {
        try {
            const token = localStorage.getItem("accessToken") || sessionStorage.getItem("accessToken");

            if (!token) {
                console.warn("No access token found. User may already be logged out.");
                clearAuthData();
                closeModel();
                return;
            }

            const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/admin/logout`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                },
            });

            if (response.ok) {
                clearAuthData();
                closeModel();
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || "Failed to log out. Please try again.");
            }
        } catch (error) {
            console.error("Logout error:", error);
        }
    };

    return (
        <>
            <button aria-label="Open user dialog" onClick={openModel}>
                <div
                    className="relative flex h-11 w-11 items-center justify-center rounded-md border border-neutral-200 text-black transition-colors dark:border-neutral-700 dark:text-white">
                    <UserIcon
                        className={clsx('h-4 transition-all ease-in-out hover:scale-110')}
                    />
                </div>
            </button>
            <Transition show={isOpen}>
                <Dialog onClose={closeModel} className="relative z-50">
                    <Transition.Child
                        as={Fragment}
                        enter="transition-all ease-in-out duration-300"
                        enterFrom="opacity-0 backdrop-blur-none"
                        enterTo="opacity-100 backdrop-blur-[.5px]"
                        leave="transition-all ease-in-out duration-200"
                        leaveFrom="opacity-100 backdrop-blur-[.5px]"
                        leaveTo="opacity-0 backdrop-blur-none"
                    >
                        <div className="fixed inset-0 bg-black/30" aria-hidden="true"/>
                    </Transition.Child>
                    <Transition.Child
                        as={Fragment}
                        enter="transition-all ease-in-out duration-300"
                        enterFrom="translate-x-full"
                        enterTo="translate-x-0"
                        leave="transition-all ease-in-out duration-200"
                        leaveFrom="translate-x-0"
                        leaveTo="translate-x-full"
                    >
                        <Dialog.Panel
                            className="fixed bottom-0 right-0 top-0 flex h-full w-full flex-col border-l border-neutral-200 bg-white/80 p-6 text-black backdrop-blur-xl md:w-[390px] dark:border-neutral-700 dark:bg-black/80 dark:text-white">

                            <div className="flex items-center justify-between mb-5">
                                <p className="text-lg font-semibold">
                                    {isAuthenticated ? "Account Info" : showSignUp ? "Sign Up" : "Login"}
                                </p>
                                <button aria-label="Close dialog" onClick={closeModel}>
                                    <CloseCart />
                                </button>
                            </div>

                            {isAuthenticated ? (
                                <UserProfile user={user!} onLogoutAction={handleLogout} />
                            ) : (
                                <>
                                    {showSignUp ? (
                                        <SignupForm onToggle={toggleForm} />
                                    ) : (
                                        <LoginForm
                                            onToggleAction={toggleForm}
                                            onSuccessAction={(userData: { name: string; email: string; }) => handleLoginSuccess(userData)}
                                        />
                                    )}
                                </>
                            )}
                        </Dialog.Panel>
                    </Transition.Child>
                </Dialog>
            </Transition>
        </>
    );
}
