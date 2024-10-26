'use client';

interface LoginFormProps {
    onToggle: () => void;
}

export default function LoginForm({ onToggle }: LoginFormProps) {
    return (
        <div className="flex flex-col justify-between overflow-hidden p-1">
            <h2 className="font-bold">Login to your account</h2>
            <div className="flex flex-col space-y-4 my-4">
                <input
                    type="text"
                    placeholder="E-mail"
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                <input
                    type="password"
                    placeholder="Password"
                    className="w-full p-2 bg-transparent border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>

            <div className="flex flex-col items-center space-y-4">
                <div className="flex items-center justify-between w-full">
                    <label className="flex items-center space-x-2 text-gray-300">
                        <input
                            type="checkbox"
                            className="text-indigo-500 border-gray-600 focus:ring-0 focus:ring-offset-0"
                        />
                        <span>Remember me</span>
                    </label>
                    <a href="#"
                       className="block p-2 text-lg underline-offset-4 hover:text-black hover:underline md:inline-block md:text-sm dark:hover:text-neutral-300">Forgot
                        password?</a>
                </div>

                <button
                    className="block w-full rounded-full bg-blue-600 p-3 text-center text-sm font-medium text-white opacity-90 hover:opacity-100"
                    type="submit">Login
                </button>

                <p className="text-gray-400">
                    Don’t have an account yet?
                    <a
                        onClick={onToggle}
                        className="cursor-pointer font-bold p-2 text-lg underline-offset-4 hover:text-black hover:underline md:inline-block md:text-sm dark:hover:text-neutral-300"
                    >
                        Sign up
                    </a>
                </p>
            </div>
        </div>
    );
}
