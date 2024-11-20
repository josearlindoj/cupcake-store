<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

class UserController
{
    public function index()
    {
        $attributes = User::all();
        return view('admin.users.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit($id)
    {
        return view('admin.users.edit', compact('id'));
    }
}
