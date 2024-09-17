<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $ingat = $request->has('remember');

            // Cek apakah email ada di database
            $user = User::where('email', $credentials['email'])->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['Email tidak terdaftar dalam sistem.'],
                ]);
            }

            // Cek password
            if (!Hash::check($credentials['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'password' => ['Password yang Anda masukkan salah.'],
                ]);
            }

            if (Auth::attempt($credentials, $ingat)) {
                $request->session()->regenerate();

                // Redirect berdasarkan role pengguna
                if ($user->role === 'admin') {
                    return redirect()->route('dashboard')->with('alert', [
                        'type' => 'success',
                        'message' => 'Login berhasil sebagai Admin!'
                    ]);
                } else {
                    return redirect('/')->with('alert', [
                        'type' => 'success',
                        'message' => 'Login berhasil sebagai User!'
                    ]);
                }
            }

            // Jika ada masalah lain saat login
            throw new \Exception('Terjadi kesalahan saat mencoba login.');

        } catch (ValidationException $e) {
            $errors = $e->errors();
            $errorMessage = isset($errors['email']) ? $errors['email'][0] : $errors['password'][0];
            return back()->withInput()->with('alert', [
                'type' => 'error',
                'message' => $errorMessage
            ]);
        } catch (\Exception $e) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi.'
            ]);
        }
    }


    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
                ],
                'phone_number' => ['nullable', 'string', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,9}$/'],
            ], [
                'password.min' => 'Password harus memiliki minimal 8 karakter.',
                'password.regex' => 'Password harus mengandung setidaknya satu huruf kecil, satu huruf besar, satu angka, dan satu karakter khusus (@$!%*?&).',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'phone_number.regex' => 'Format nomor telepon tidak valid. Gunakan format yang benar (contoh: +628123456789).',
            ]);

            $user = User::create([
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'phone_number' => $validatedData['phone_number'],
            ]);

            Auth::login($user);

            return redirect('dashboard')->with('alert', [
                'type' => 'success',
                'message' => 'Registrasi berhasil!'
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput()->with('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan validasi. Silakan periksa kembali input Anda.'
            ]);
        } catch (\Exception $e) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan yang tidak terduga selama registrasi. Silakan coba lagi.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('alert', [
            'type' => 'success',
            'message' => 'Anda telah berhasil logout.'
        ]);
    }

}
