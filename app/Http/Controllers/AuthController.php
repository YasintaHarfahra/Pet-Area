<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('auth.reset_password', [
            'email' => $request->query('email'),
            'token' => $token,
        ]);
    }

    /**
     * Show forgot password form for dokter
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    /**
     * Handle forgot password request for dokter
     */
    public function sendForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Password::sendResetLink($request->only('email'));

        return back()->with('status', 'Jika email tersebut terdaftar, tautan reset kata sandi akan dikirim.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()->withErrors([
            'email' => 'Tautan reset kata sandi tidak valid atau sudah kedaluwarsa.',
        ]);
    }

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
            'role_type' => 'required|in:dokter,admin',
        ]);

        $loginField = $request->input('login_field');
        $password = $request->input('password');
        $roleType = $request->input('role_type');
        $remember = $request->boolean('remember');

        // Cari user berdasarkan role
        if ($roleType === 'dokter') {
            $user = User::where('email', $loginField)->where('role', 'dokter')->first();
        } else {
            $user = User::where('username', $loginField)->where('role', 'admin')->first();
        }

        // CSRF sudah divalidasi oleh middleware VerifyCsrfToken (tidak perlu cek manual)

        // Jika user tidak ditemukan
        if (!$user) {
            return back()->withErrors([
                'login_field' => 'Akun tidak ditemukan.',
            ])->onlyInput('login_field');
        }

        // Verifikasi password
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'login_field' => 'Password salah.',
            ])->onlyInput('login_field');
        }

        // Login user ke guard yang sesuai
        $guard = $roleType === 'dokter' ? 'dokter' : 'admin';
        Auth::guard($guard)->login($user, $remember);
        $request->session()->regenerate();
        auth()->shouldUse($guard);

        // Redirect langsung ke dashboard sesuai role
        if ($guard === 'dokter') {
            return redirect()->route('dokter.dashboard');
        } else {
            return redirect()->route('admin.dashboard');
        }
    }

    public function logout(Request $request)
    {
        // Logout semua guards (admin dan dokter)
        Auth::guard('admin')->logout();
        Auth::guard('dokter')->logout();

        // Invalidate dan regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    protected function redirectBasedOnRole($user)
    {
        if ($user->role === 'dokter') { return redirect()->intended(route('dokter.dashboard')); }
        elseif ($user->role === 'admin') { return redirect()->intended(route('admin.dashboard')); }
        return redirect()->intended('/home');
    }
}