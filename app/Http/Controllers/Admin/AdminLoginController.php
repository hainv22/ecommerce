<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

class AdminLoginController extends Controller
{
    public function index(Request $request)
    {
        $this->saveLogWhenLogin('login index', $request);
        return view('admin.auth.login');
    }

    public function postLoginAdmin(Request $request)
    {
        $this->saveLogWhenLogin('postLoginAdmin', $request);
        $data = $request->only('email', 'password');
        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin-ecommerce');
        }
        return redirect()->back();
    }

    public function getLogoutAdmin(Request $request)
    {
        $this->saveLogWhenLogin('logout', $request);
        Auth::logout();
        return redirect('/admin-auth/login');
    }

    public function saveLogWhenLogin($type, $request, $content = null)
    {
        Log::create([
            'user_id' => 99999999,
            'type' => $type,
            'content' => $content,
            'data' => json_encode($request->all() + array('ip' => $request->ip(), 'method' => $request->method(), 'fullUrl' => $request->fullUrl(), 'type' => $type))
        ]);
    }
}
