<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login_proses(Request $request)
    {
        // dd($request->all());
        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $data = [
            $loginType => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($data)) {
            $user = Auth::user();
            
            // Cek peran pengguna setelah login berhasil
            if ($user->active == 0) {
                return redirect()->route('auth.login')->with('failed', 'Your Account was inactive, contact your admin');
            }
        
            // Redirect sesuai peran pengguna jika status aktif
            if ($user->role == 0) {
                return redirect()->route('direktur.dashboard')->with('success', 'Selamat Datang Kembali ' . Auth::user()->name);
            } elseif ($user->role == 1) {
                return redirect()->route('pegawai.dashboard')->with('success', 'Selamat Datang Kembali ' . Auth::user()->name);
            } elseif ($user->role == 2) {
                return redirect()->route('penerima.dashboard')->with('success', 'Selamat Datang Kembali ' . Auth::user()->name);
            }
        } else {
            // Redirect kembali ke halaman login jika gagal
            return redirect()->route('auth.login')->with('failed', 'Username atau Password anda salah!');
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login')->with('success', 'Kamu berhasil Logout');
    }
}
