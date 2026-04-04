<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }
            // Auto compress & resize sebelum simpan
            $user->profile_photo_path = $this->compressAndSavePhoto($request->file('photo'));
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Kompres & resize foto profil, lalu simpan ke storage/public.
     *
     * - Format input  : JPEG, JPG, PNG, GIF, WEBP (semua resolusi)
     * - Format output : JPEG, kualitas 85%
     * - Dimensi max   : 800 x 800 px (proporsional)
     */
    private function compressAndSavePhoto($file): string
    {
        $maxDimension = 800;  // max width/height dalam pixel
        $quality      = 85;   // JPEG quality (0-100)

        $mime    = $file->getMimeType();
        $tmpPath = $file->getRealPath();

        // Buat resource GD dari file upload
        $source = match (true) {
            in_array($mime, ['image/jpeg', 'image/jpg']) => imagecreatefromjpeg($tmpPath),
            $mime === 'image/png'                        => imagecreatefrompng($tmpPath),
            $mime === 'image/gif'                        => imagecreatefromgif($tmpPath),
            $mime === 'image/webp'                       => imagecreatefromwebp($tmpPath),
            default                                      => imagecreatefromjpeg($tmpPath),
        };

        [$origW, $origH] = getimagesize($tmpPath);

        // Hitung dimensi baru dengan menjaga aspek rasio
        if ($origW > $maxDimension || $origH > $maxDimension) {
            if ($origW >= $origH) {
                $newW = $maxDimension;
                $newH = (int) round($origH * ($maxDimension / $origW));
            } else {
                $newH = $maxDimension;
                $newW = (int) round($origW * ($maxDimension / $origH));
            }
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        // Buat kanvas baru
        $canvas = imagecreatetruecolor($newW, $newH);

        // Isi background putih (penting untuk PNG transparan yang dikonversi ke JPEG)
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);

        // Salin & resize gambar ke kanvas
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        // Tentukan path tujuan
        $filename    = 'profile-photos/' . Str::uuid() . '.jpg';
        $storagePath = storage_path('app/public/' . $filename);

        // Pastikan direktori ada
        $dir = dirname($storagePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Simpan sebagai JPEG dengan kompresi
        imagejpeg($canvas, $storagePath, $quality);

        // Bebaskan memori
        imagedestroy($source);
        imagedestroy($canvas);

        return $filename;
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Aksi ini tidak diizinkan. Hanya Administrator yang dapat menghapus akun.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
