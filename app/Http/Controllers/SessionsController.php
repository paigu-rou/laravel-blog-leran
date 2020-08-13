<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create() {
        return view('sessions.create');
    }

    public function store(Request $request) {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        // 验证失败
        if (Auth::attempt($credentials, $request->has('remember'))) {
            session()->flash('success', '登录成功！');
            return redirect()->route('users.show', Auth::user());
        }
        session()->flash('danger', '账号或密码错误！');
        return redirect('/login');
    }

    public function destroy() {
        Auth::logout();

        session()->flash('success', '您已注销！');
        return redirect('/login');
    }
}
