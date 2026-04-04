<?php

$files = [
    __DIR__ . '/app/Http/Controllers/Teacher/MaterialController.php',
    __DIR__ . '/app/Services/MaterialService.php',
    __DIR__ . '/app/Policies/MaterialPolicy.php',
    __DIR__ . '/app/Http/Requests/StoreMaterialRequest.php',
    __DIR__ . '/app/Http/Requests/UpdateMaterialRequest.php',
];

$translations = [
    "Display a listing of the materials." => "Menampilkan daftar materi.",
    "Show the form for creating a new material." => "Menampilkan formulir pendaftaran materi baru.",
    "Store a newly created material in storage." => "Menyimpan data materi baru ke dalam basis data.",
    "Display the specified material." => "Menampilkan detail materi yang dipilih.",
    "Show the form for editing the specified material." => "Menampilkan formulir untuk mengubah materi.",
    "Update the specified material in storage." => "Memperbarui data materi di dalam basis data.",
    "Remove the specified material from storage." => "Menghapus data materi beserta file fisiknya.",
    "Add a comment to the material." => "Menambahkan komentar baru ke dalam materi.",
    "Pin or unpin a comment." => "Menyematkan (pin) atau melepas sematan komentar.",
    "Delete a comment." => "Menghapus komentar dari materi.",
    "Store new material and notify assigned students." => "Menyimpan materi baru dan mengirim notifikasi ke siswa.",
    "Update an existing material safely." => "Memperbarui data dan file materi dengan aman.",
    "Send internal notification to all enrolled students regarding this new material." => "Mengirim notifikasi internal ke seluruh siswa yang terdaftar di kelas mengenai materi baru.",
    "Determine whether the user can modify the material." => "Menentukan apakah pengguna berhak (punya akses) untuk mengubah materi ini.",
    "Determine whether the user can delete the material." => "Menentukan apakah pengguna berhak menghapus materi.",
    "Determine whether the user can view the material." => "Menentukan apakah pengguna diizinkan untuk melihat detail materi.",
    "Determine if the user is authorized to make this request." => "Menentukan apakah pengguna diizinkan untuk memproses permintaan logika formulir ini.",
    "Get the validation rules that apply to the request." => "Mengembalikan aturan-aturan validasi formulir (form validation rules)."
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $original = $content;
        
        foreach ($translations as $en => $id) {
            $content = str_replace($en, $id, $content);
        }
        
        if ($content !== $original) {
            file_put_contents($file, $content);
            echo "Translated docs in: " . basename($file) . "\n";
        }
    }
}
