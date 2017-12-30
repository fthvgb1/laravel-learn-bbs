<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdate;
use App\Models\User;

class UsersController extends Controller
{
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(User $user, UserUpdate $request)
    {
        $attribute = $request->only(['name', 'email', 'introduction']);
        if ($request->file('avatar')) {
            $user->avatar = $request->file('avatar')->store('public/avatar');
        }
        $user->update($attribute);
        return redirect()->route('users.show', [$user])->with('success', '更新成功！');

    }
}
