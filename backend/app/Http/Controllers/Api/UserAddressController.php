<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    /**
     * Store a new user address.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['user_id'] = Auth::id();

        $address = UserAddress::create($validated);

        return response()->json(['message' => 'Address created successfully', 'address' => $address], 201);
    }

    /**
     * Update an existing user address.
     *
     * @param Request $request
     * @param UserAddress $address
     * @return JsonResponse
     */
    public function update(Request $request, UserAddress $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $address->update($validated);

        return response()->json(['message' => 'Address updated successfully', 'address' => $address]);
    }

    /**
     * Create or update a user address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upsert(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable|exists:user_addresses,id',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        if (@$validated['address_id']) {
            $address = UserAddress::where('id', $validated['address_id'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            $address->update($validated);

            return response()->json(['message' => 'Address updated successfully', 'address' => $address], 200);
        } else {
            // Create a new address
            $validated['user_id'] = $user->id;
            $address = UserAddress::create($validated);

            return response()->json(['message' => 'Address created successfully', 'address' => $address], 201);
        }
    }
}

