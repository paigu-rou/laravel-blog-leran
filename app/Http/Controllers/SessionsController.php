<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{

    public function __construct() {
        $this->middleware('guest', ['only' => ['create']]);
    }

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
            // 验证是否激活
            if (Auth::user()->activated) {
                session()->flash('success', '登录成功！');
                return redirect()->intended(route('users.show', Auth::user()));
            }
            else {
                // attempt 验证通过，需要先清除登录状态
                Auth::logout();
                session()->flash('danger', '请先到邮箱激活账号');
                return redirect('/login');
            }
        }
        session()->flash('danger', '邮箱或密码错误！');
        return redirect('/login');
    }

    public function destroy() {
        Auth::logout();

        session()->flash('success', '您已注销！');
        return redirect('/login');
    }
}
