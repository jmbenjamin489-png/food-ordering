<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        if (!config('services.google.client_id') || !config('services.google.client_secret') || !config('services.google.redirect')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google login is not configured yet. Please contact the administrator.',
            ]);
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'access_type' => 'online',
            'prompt' => 'select_account',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function handleGoogleCallback(Request $request)
    {
        if ($request->filled('error')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google login was cancelled or denied.',
            ]);
        }

        $request->validate([
            'code' => 'required|string',
            'state' => 'required|string',
        ]);

        if ($request->state !== $request->session()->pull('google_oauth_state')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Invalid OAuth state. Please try again.',
            ]);
        }

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $request->code,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]);

        if (!$tokenResponse->successful()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google login failed during token exchange.',
            ]);
        }

        $accessToken = $tokenResponse->json('access_token');
        $userInfoResponse = Http::withToken($accessToken)
            ->get('https://openidconnect.googleapis.com/v1/userinfo');

        if (!$userInfoResponse->successful()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google login failed while fetching your profile.',
            ]);
        }

        $googleUser = $userInfoResponse->json();

        if (empty($googleUser['email'])) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google account email was not provided.',
            ]);
        }

        $user = User::where('google_id', $googleUser['sub'] ?? null)->first();

        if (!$user) {
            $user = User::where('email', $googleUser['email'])->first();
        }

        if ($user) {
            $user->update([
                'google_id' => $googleUser['sub'] ?? $user->google_id,
                'google_avatar' => $googleUser['picture'] ?? $user->google_avatar,
                'auth_provider' => 'google',
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        } else {
            $user = User::create([
                'name' => $googleUser['name'] ?? 'Google User',
                'email' => $googleUser['email'],
                'google_id' => $googleUser['sub'] ?? null,
                'google_avatar' => $googleUser['picture'] ?? null,
                'password' => Hash::make(Str::random(40)),
                'phone' => null,
                'address' => null,
                'role' => 'customer',
                'auth_provider' => 'google',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return $user->isAdmin()
            ? redirect()->intended('/admin/dashboard')
            : redirect()->intended('/');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }
            
            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful! Welcome to our restaurant.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }

    public function profile()
    {
        $user = Auth::user();

        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
