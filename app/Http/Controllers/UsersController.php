<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdate;
use App\Models\User;
use Intervention\Image\Facades\Image;

class UsersController extends Controller
{
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(User $user, UserUpdate $request)
    {
        $this->authorize('update', $user);
        $attribute = $request->only(['name', 'email', 'introduction']);
        if ($image = $request->file('avatar')) {
            $user->avatar = $image->store('public/avatar');
            $file = Image::make(storage_path('app/' . $user->avatar));
            $file->resize(1280, null, function ($constraint) {

                // 设定宽度是 $max_width，高度等比例双方缩放
                $constraint->aspectRatio();

                // 防止裁图时图片尺寸变大
                $constraint->upsize();
            });

            // 对图片修改后进行保存
            $file->save();

        }
        $user->update($attribute);
        return redirect()->route('users.show', [$user])->with('success', '更新成功！');

    }
}
