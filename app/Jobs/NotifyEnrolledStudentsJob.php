<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyEnrolledStudentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Course $course;
    protected string $title;
    protected string $message;
    protected string $type;
    protected int $relatedId;

    /**
     * Create a new job instance.
     *
     * @param Course $course
     * @param string $title
     * @param string $message
     * @param string $type
     * @param int $relatedId
     */
    public function __construct(Course $course, string $title, string $message, string $type, int $relatedId)
    {
        $this->course = $course;
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->relatedId = $relatedId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Loop through all enrolled students and create a notification
        // For extremely large classes, this chunking prevents memory exhaustion
        $this->course->students()->chunk(100, function ($students) {
            $notifications = [];
            foreach ($students as $student) {
                $notifications[] = [
                    'user_id' => $student->id,
                    'title' => $this->title,
                    'message' => $this->message,
                    'type' => $this->type,
                    'related_id' => $this->relatedId,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // Batched insert for massive performance speedup
            Notification::insert($notifications);
        });
    }
}
