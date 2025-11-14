<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     *Sanitasi input untuk mencegah XSS & HTML berbahaya
     */
    private function sanitizeInput($value)
    {
        if (is_array($value)) {
            return array_map(fn($v) => $this->sanitizeInput($v), $value);
        }

        // Hapus tag HTML, escape karakter berbahaya, dan trim spasi
        $value = strip_tags($value);
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $value = trim($value);

        return $value;
    }

    /**
     * Menampilkan halaman profil pengguna
     */
    public function index()
    {
        $authUser = Auth::user();

        $user = (object) [
            'name'   => $authUser->name,
            'nik'    => $authUser->nik,
            'no_hp'  => $authUser->no_hp,
            'alamat' => $authUser->alamat,
            'email'  => $authUser->email,
        ];

        return view('main.user.profile-user', [
            'title' => 'Profil Pengguna',
            'userName' => $user->name,
            'user' => $user,
        ]);
    }

    /**
     * Update profil pengguna via AJAX (dengan sanitasi)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        try {
            $validator = Validator::make($request->all(), [
                'nama'   => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s\'\-]+$/'],
                'nik'    => ['required', 'digits:16', 'unique:users,nik,' . $user->id],
                'no_hp'  => ['required', 'string', 'regex:/^[0-9+\-\s]{9,15}$/', 'unique:users,no_hp,' . $user->id],
                'alamat' => ['required', 'string', 'max:255'],
                'email'  => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email,' . $user->id],
            ], [
                'nama.required' => 'Nama wajib diisi.',
                'nama.regex' => 'Nama hanya boleh berisi huruf, spasi, tanda petik, dan strip.',
                'nik.required' => 'NIK wajib diisi.',
                'nik.digits' => 'NIK harus 16 digit.',
                'nik.unique' => 'NIK sudah terdaftar.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.regex' => 'Nomor HP hanya boleh angka, +, -, dan spasi.',
                'no_hp.unique' => 'Nomor HP sudah terdaftar.',
                'alamat.required' => 'Alamat wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
            ]);

            if ($validator->fails()) {
                Log::warning('Validasi update profil gagal', [
                    'user_id' => $user->id,
                    'errors' => $validator->errors()->toArray(),
                    'input'  => $request->all(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal. Periksa kembali input Anda.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            //Sanitasi semua input sebelum disimpan
            $safeNama   = ucwords(strtolower($this->sanitizeInput($request->nama)));
            $safeNik    = $this->sanitizeInput($request->nik);
            $safeNoHp   = $this->sanitizeInput($request->no_hp);
            $safeAlamat = $this->sanitizeInput($request->alamat);
            $safeEmail  = strtolower($this->sanitizeInput($request->email));

            $user->update([
                'name'   => $safeNama,
                'nik'    => $safeNik,
                'no_hp'  => $safeNoHp,
                'alamat' => $safeAlamat,
                'email'  => $safeEmail,
            ]);

            Log::info('Profil pengguna berhasil diupdate', [
                'user_id' => $user->id,
                'input'   => $request->except(['_token']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data profil berhasil diperbarui!',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Terjadi error saat update profil', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Ubah password pengguna via AJAX (aman)
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.min'          => 'Password baru minimal 8 karakter.',
            'new_password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validasi ganti password gagal', [
                'user_id' => $user->id,
                'errors'  => $validator->errors()->toArray(),
                'input'   => $request->except(['current_password', 'new_password', 'new_password_confirmation']),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // ✅ Verifikasi password lama
        if (!Hash::check($request->current_password, $user->password)) {
            Log::warning('Password saat ini salah', ['user_id' => $user->id]);

            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah.',
                'errors'  => ['current_password' => ['Password saat ini salah.']],
            ], 403);
        }

        try {
            $user->password = Hash::make($this->sanitizeInput($request->new_password));
            $user->save();

            Log::info('Password pengguna berhasil diupdate', [
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diperbarui!',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Terjadi error saat update password', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server saat menyimpan password.',
            ], 500);
        }
    }
}
