<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Notification;
use App\Models\Submission;

class AssignmentService
{
    /**
     * Menyimpan data tugas baru dan mengirim notifikasi ke siswa.
     *
     * @param array $data Data tugas yang sudah divalidasi
     * @return Assignment
     */
    public function createAssignment(array $data): Assignment
    {
        $assignment = Assignment::create($data);

        $this->notifyEnrolledStudents($assignment);

        return $assignment;
    }

    /**
     * Memperbarui rincian tugas yang ada.
     *
     * @param Assignment $assignment Objek tugas yang diubah
     * @param array $data Data tugas yang sudah divalidasi
     * @return Assignment
     */
    public function updateAssignment(Assignment $assignment, array $data): Assignment
    {
        $assignment->update($data);

        return $assignment;
    }

    /**
     * Menyimpan penilaian dan balasan (feedback) untuk submission siswa.
     *
     * @param Submission $submission
     * @param array $data Input nilai dan feedback yang divalidasi
     * @return Submission
     */
    public function gradeSubmission(Submission $submission, array $data): Submission
    {
        $submission->update($data);

        return $submission;
    }

    /**
     * Mengirim notifikasi internal ke seluruh siswa mengenai adanya tugas baru.
     *
     * @param Assignment $assignment
     * @return void
     */
    protected function notifyEnrolledStudents(Assignment $assignment): void
    {
        $course = $assignment->course;
        
        \App\Jobs\NotifyEnrolledStudentsJob::dispatch(
            $course,
            __('Tugas Baru'),
            sprintf("Tugas baru '%s' telah ditambahkan ke kelas '%s'. Batas waktu: %s", 
                $assignment->title, 
                $course->title, 
                $assignment->deadline->format('d M Y H:i')),
            'assignment',
            $assignment->id
        );
    }
}
