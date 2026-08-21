<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {}

    public function showLogin()
    {
        return view('pages.authentications.auth-login-basic');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Log aktivitas login
            $this->activityLogService->logLogin();

            return redirect()->intended($this->homePath());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        // Check if registration is allowed
        if (get_setting('allow_registration', '1') !== '1') {
            return redirect()->route('login')->with('error', 'Registrasi saat ini sedang dinonaktifkan.');
        }

        return view('pages.authentications.auth-register-basic');
    }

    public function register(Request $request)
    {
        if (get_setting('allow_registration', '1') !== '1') {
            return redirect()->route('login')->with('error', 'Registrasi saat ini sedang dinonaktifkan.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        // Find Customer Role
        $customerRole = Role::where('slug', 'customer')->first();
        if (! $customerRole) {
            $customerRole = Role::where('slug', 'user')->first();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $customerRole ? $customerRole->id : null,
        ]);

        Auth::login($user);

        // Log aktivitas register
        $this->activityLogService->log('register', 'User baru terdaftar sebagai Customer', $user);

        return redirect()->intended($this->homePath())->with('success', 'Registrasi berhasil! Selamat datang di BusGo.');
    }

    /**
     * Arahkan user ke dashboard sesuai perannya setelah login.
     */
    protected function homePath(): string
    {
        $user = auth()->user();

        if ($user && $user->isAdmin()) {
            return route('admin.dashboard');
        }

        return route('customer.dashboard');
    }

    public function logout(Request $request)
    {
        // Log aktivitas logout sebelum logout
        $this->activityLogService->logLogout();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
