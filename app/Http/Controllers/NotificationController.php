<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tandai notifikasi sebagai sudah dibaca lalu redirect ke sumber notifikasi.
     */
    public function read(Notification $notification): RedirectResponse
    {
        // Pastikan notifikasi milik user yang sedang login
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        // Tandai sudah dibaca
        $notification->update(['is_read' => true]);

        $role = auth()->user()->role; // 'student', 'teacher', 'admin'

        // Tentukan URL tujuan berdasarkan tipe notifikasi
        $url = $this->resolveUrl($notification, $role);

        return redirect($url);
    }

    /**
     * Tandai SEMUA notifikasi user sebagai sudah dibaca.
     */
    public function readAll(Request $request): RedirectResponse
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', __('Semua notifikasi telah ditandai sudah dibaca.'));
    }

    /**
     * Resolve URL tujuan berdasarkan tipe notifikasi dan role user.
     */
    private function resolveUrl(Notification $notification, string $role): string
    {
        $type      = $notification->type;
        $relatedId = $notification->related_id;

        // Mapping tipe notifikasi → route student
        if ($role === 'student') {
            return match ($type) {
                'assignment' => $relatedId
                    ? route('student.assignments.show', $relatedId)
                    : route('student.assignments.index'),
                'material'   => $relatedId
                    ? route('student.materials.show', $relatedId)
                    : route('student.materials.index'),
                'quiz'       => $relatedId
                    ? route('student.quizzes.show', $relatedId)
                    : route('student.quizzes.index'),
                'grade'      => route('student.grades.index'),
                'attendance' => route('student.attendance.index'),
                default      => route('student.dashboard'),
            };
        }

        // Mapping tipe notifikasi → route teacher
        if ($role === 'teacher') {
            return match ($type) {
                'assignment' => $relatedId
                    ? route('teacher.assignments.show', $relatedId)
                    : route('teacher.courses.index'),
                'material'   => $relatedId
                    ? route('teacher.materials.show', $relatedId)
                    : route('teacher.courses.index'),
                default      => route('teacher.dashboard'),
            };
        }

        // Admin atau lainnya → dashboard
        return route('admin.dashboard');
    }
}
