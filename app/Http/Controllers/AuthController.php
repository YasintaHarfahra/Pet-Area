<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $rememberRole = $request->cookie('remember_role_type', 'dokter');
        $rememberLogin = $request->cookie('remember_login_field', '');
        $rememberChecked = (bool) $request->cookie('remember_flag', false);

        return view('auth.login', compact('rememberRole', 'rememberLogin', 'rememberChecked'));
    }

    public function login(Request $request)
    {
        // Validasi awal
        $request->validate([
            'login_field' => 'required|string',
            'password' => 'required|string',
            'role_type' => 'required|in:dokter,admin', // Validasi role yang dipilih
        ]);

        $loginField = $request->input('login_field');
        $password = $request->input('password');
        $roleType = $request->input('role_type'); // Ambil role yang dipilih user

        $credentials = [
            'password' => $password,
        ];

        // Sesuaikan kredensial berdasarkan role_type yang dipilih user
        if ($roleType === 'dokter') {
            $credentials['email'] = $loginField;
        } elseif ($roleType === 'admin') {
            $credentials['username'] = $loginField;
        } else {
            // Fallback jika role_type tidak valid (harusnya sudah dicegah oleh validasi 'in:dokter,admin')
            return back()->withErrors([
                'login_field' => 'Role yang dipilih tidak valid.',
            ])->onlyInput('login_field');
        }

        $remember = $request->boolean('remember');
        $guard = $roleType === 'dokter' ? 'dokter' : 'admin';

        if (Auth::guard($guard)->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::guard($guard)->user();
            if ($user && $user->role === $roleType) {
                auth()->shouldUse($guard);
                $response = $this->redirectBasedOnRole($user);

                if ($remember) {
                    return $response->withCookies([
                        cookie('remember_role_type', $roleType, 60 * 24 * 30),
                        cookie('remember_login_field', $loginField, 60 * 24 * 30),
                        cookie('remember_flag', true, 60 * 24 * 30),
                    ]);
                }

                return $response->withCookies([
                    Cookie::forget('remember_role_type'),
                    Cookie::forget('remember_login_field'),
                    Cookie::forget('remember_flag'),
                ]);
            } else {
                Auth::guard($guard)->logout();
                return back()->withErrors([
                    'login_field' => 'Role yang Anda pilih tidak cocok dengan akun ini. Silakan coba role lain.',
                ])->onlyInput('login_field');
            }
        }

        // Jika Auth::attempt gagal
        return back()->withErrors([
            'login_field' => 'Kombinasi ' . ($roleType === 'dokter' ? 'email' : 'username') . ' dan password tidak cocok.',
        ])->onlyInput('login_field');
    }

    public function logout(Request $request)
    {
        $guard = $request->input('guard');
        $guardsToLogout = [];

        if ($guard && in_array($guard, ['admin', 'dokter'])) {
            $guardsToLogout[] = $guard;
        } else {
            $guardsToLogout = ['admin', 'dokter'];
        }

        foreach ($guardsToLogout as $logoutGuard) {
            if (Auth::guard($logoutGuard)->check()) {
                Auth::guard($logoutGuard)->logout();
            }
        }

        $request->session()->regenerateToken();
        return redirect('/login');
    }

    protected function redirectBasedOnRole($user)
    {
        if ($user->role === 'dokter') { return redirect()->intended(route('dashboard.dokter')); }
        elseif ($user->role === 'admin') { return redirect()->intended(route('dashboard.admin')); }
        return redirect()->intended('/home');
    }
}