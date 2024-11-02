// Profile.tsx
'use client';

import React, { useEffect, useState } from "react";
import { PlusIcon } from "@heroicons/react/24/outline";
import ModalAddress from "@/components/layout/modal-address";
import axios from "axios";

interface UserProfileProps {
    user: {
        name: string;
        email: string;
    };
    onLogout: () => void;
}

export default function Profile({ user, onLogout }: UserProfileProps) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [address, setAddress] = useState('');
    const [city, setCity] = useState('');
    const [state, setState] = useState('');
    const [postalCode, setPostalCode] = useState('');
    const [country, setCountry] = useState('USA');
    const [phone, setPhone] = useState('');
    const [errors, setErrors] = useState<{ [key: string]: string[] }>({});
    const [successMessage, setSuccessMessage] = useState("");

    useEffect(() => {
        // Load user data from sessionStorage on component mount
        const userData = sessionStorage.getItem("user");
        if (userData) {
            const parsedData = JSON.parse(userData);

            const userAddress = parsedData?.addresses[0];

            if (userAddress) {
                setAddress(userAddress.address || '');
                setCity(userAddress.city || '');
                setState(userAddress.state || '');
                setPostalCode(userAddress.postal_code || '');
                setCountry(userAddress.country || 'USA');
                setPhone(userAddress.phone || '');
            }
        }
    }, []);

    const openModal = () => setIsModalOpen(true);
    const closeModal = () => {
        setIsModalOpen(false);
        setErrors({});
        setSuccessMessage("");
    };

    const handleSubmit = async () => {
        const addressData = {
            address,
            city,
            state,
            postal_code: postalCode,
            country,
            phone,
        };

        // Clear previous messages
        setErrors({});
        setSuccessMessage("");

        try {
            const token = sessionStorage.getItem('accessToken');
            const response = await axios.post(
                `${process.env.NEXT_PUBLIC_API_URL}/admin/user-addresses/upsert`,
                addressData,
                {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                    },
                }
            );

            if (response.status === 201 || response.status === 200) {
                setSuccessMessage("Address saved successfully");
                closeModal();
            }
        } catch (error) {
            if (axios.isAxiosError(error) && error.response?.status === 422) {
                setErrors(error.response.data.errors || {});
            } else {
                setErrors({ general: ["An error occurred. Please try again."] });
            }
        }
    };

    return (
        <>
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

                <button
                    aria-label="Open Address Modal"
                    onClick={openModal}
                    className="relative flex w-full items-center justify-center rounded-full bg-blue-600 p-4 tracking-wide text-white cursor-pointer"
                >
                    Address
                </button>
            </div>

            <ModalAddress
                isOpen={isModalOpen}
                title="Address"
                onClose={closeModal}
                onAccept={handleSubmit}
            >
                <form className="space-y-4">
                    <input
                        type="text"
                        placeholder="Address"
                        value={address}
                        onChange={(e) => setAddress(e.target.value)}
                        className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    {errors.address && <p className="text-red-500">{errors.address[0]}</p>}

                    <input
                        type="text"
                        placeholder="City"
                        value={city}
                        onChange={(e) => setCity(e.target.value)}
                        className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    {errors.city && <p className="text-red-500">{errors.city[0]}</p>}

                    <input
                        type="text"
                        placeholder="State"
                        value={state}
                        onChange={(e) => setState(e.target.value)}
                        className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    {errors.state && <p className="text-red-500">{errors.state[0]}</p>}

                    <input
                        type="text"
                        placeholder="Postal Code"
                        value={postalCode}
                        onChange={(e) => setPostalCode(e.target.value)}
                        className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    {errors.postal_code && <p className="text-red-500">{errors.postal_code[0]}</p>}

                    <input
                        type="text"
                        placeholder="Country"
                        value={country}
                        onChange={(e) => setCountry(e.target.value)}
                        className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    {errors.country && <p className="text-red-500">{errors.country[0]}</p>}

                    <input
                        type="text"
                        placeholder="Phone"
                        value={phone}
                        onChange={(e) => setPhone(e.target.value)}
                        className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                    {errors.phone && <p className="text-red-500">{errors.phone[0]}</p>}
                </form>

                {errors.general && <p className="text-red-500">{errors.general[0]}</p>}
                {successMessage && <p className="text-green-500">{successMessage}</p>}
            </ModalAddress>
        </>
    );
}
