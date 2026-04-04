<?php

namespace App\Services;

use App\Models\Material;
use App\Models\Course;
use App\Models\Notification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MaterialService
{
    /**
     * Menyimpan materi baru dan mengirim notifikasi ke siswa.
     *
     * @param array $data Data materi yang sudah divalidasi
     * @param UploadedFile|null $file File opsional yang diunggah
     * @return Material
     */
    public function createMaterial(array $data, ?UploadedFile $file): Material
    {
        $filePath = null;
        if ($file) {
            $filePath = $file->store('materials', 'public');
        }

        $material = Material::create([
            'course_id'  => $data['course_id'],
            'title'      => $data['title'],
            'type'       => $data['type'],
            'file_path'  => $filePath,
            'video_link' => $data['video_link'] ?? null,
        ]);

        $this->notifyEnrolledStudents($material);

        return $material;
    }

    /**
     * Memperbarui data dan file materi dengan aman.
     * 
     * @param Material $material Objek materi yang sedang diubah
     * @param array $data Data materi yang sudah divalidasi
     * @param UploadedFile|null $file File baru opsional yang diunggah
     * @return Material
     */
    public function updateMaterial(Material $material, array $data, ?UploadedFile $file): Material
    {
        $filePath = $material->file_path;
        if ($file) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $file->store('materials', 'public');
        }

        $material->update([
            'course_id'  => $data['course_id'],
            'title'      => $data['title'],
            'type'       => $data['type'],
            'file_path'  => $filePath,
            'video_link' => $data['video_link'] ?? null,
        ]);

        return $material;
    }

    /**
     * Mengirim notifikasi internal ke seluruh siswa yang terdaftar di kelas mengenai materi baru.
     * 
     * @param Material $material
     * @return void
     */
    protected function notifyEnrolledStudents(Material $material): void
    {
        $course = $material->course;
        
        \App\Jobs\NotifyEnrolledStudentsJob::dispatch(
            $course,
            __('Materi Baru Tersedia'),
            sprintf("Materi baru '%s' telah diunggah ke kelas '%s'.", $material->title, $course->title),
            'material',
            $material->id
        );
    }
}
