'use client';

import { UserIcon } from "@heroicons/react/24/outline";
import { Dialog, Transition } from '@headlessui/react';
import clsx from "clsx";
import { Fragment, useState } from "react";
import CloseCart from "@/components/cart/close-cart";
import LoginForm from "@/components/layout/auth/login-form";
import SignupForm from "@/components/layout/auth/signup-form";

export default function UserDialog() {
    const [isOpen, setIsOpen] = useState(false);
    const [showSignUp, setShowSignUp] = useState(false);

    const openModel = () => setIsOpen(true);
    const closeModel = () => setIsOpen(false);
    const toggleForm = () => setShowSignUp((prev) => !prev);

    return (
        <>
            <button aria-label="Open cart" onClick={openModel}>
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
                                <p className="text-lg font-semibold">{showSignUp ? "Sign Up" : "Login"}</p>
                                <button aria-label="Close cart" onClick={closeModel}>
                                    <CloseCart/>
                                </button>
                            </div>

                            {showSignUp ? (
                                <SignupForm onToggle={toggleForm} />
                            ) : (
                                <LoginForm onToggle={toggleForm} />
                            )}

                        </Dialog.Panel>
                    </Transition.Child>
                </Dialog>
            </Transition>
        </>
    );
}
