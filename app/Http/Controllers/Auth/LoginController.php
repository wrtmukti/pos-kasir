<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;
    public function redirectTo()
    {
        switch (Auth::user()->role) {
            case 0:
                $this->redirectTo = '/admin/';
                return $this->redirectTo;
                break;
            case 1:
                $this->redirectTo = '/admin/';
                return $this->redirectTo;
                break;
                // default:
                //     $this->redirectTo = '/web';
                //     return $this->redirectTo;
        }

        // return $next($request);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        // validasi awal (akan mengisi $errors untuk @error di blade)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ambil input
        $email = $request->input('email');
        $password = $request->input('password');

        // cari user berdasarkan email
        $user = User::where('email', $email)->first();

        if (! $user) {
            // email tidak ditemukan -> kirim error ke field email
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // cek password
        if (! Hash::check($password, $user->password)) {
            // password salah -> kirim error ke field password
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'Password salah.']);
        }

        // lakukan login
        Auth::login($user);

        // optional: jika mau cek apakah user aktif/diblokir bisa ditambahkan di sini

        // redirect berdasarkan role (sesuaikan rute)
        if ($user->role == 0 || $user->role == 1) {
            return redirect()->to('/admin/');
        }

        // fallback
        return redirect()->intended('/login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
