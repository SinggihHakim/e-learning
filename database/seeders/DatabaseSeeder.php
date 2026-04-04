<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\GradeComponent;
use App\Models\Notification;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === 1. ADMINISTRATOR ===
        $admin = User::create([
            'name' => 'Budi Santoso (Admin Utama)',
            'email' => 'admin@sman1.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Pendidikan No. 1, Jakarta',
        ]);

        // === 2. TEACHERS ===
        $teacher1 = User::create([
            'name' => 'Drs. Sudirman, M.Si',
            'email' => 'sudirman@sman1.sch.id',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'bio' => 'Guru Matematika Berpengalaman 20 Tahun',
            'birth_place' => 'Bandung',
            'birth_date' => '1975-04-12',
            'gender' => 'Laki-laki',
        ]);

        $teacher2 = User::create([
            'name' => 'Dra. Rina Sriwati',
            'email' => 'rina@sman1.sch.id',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'bio' => 'Guru Fisika Lulusan Terbaik ITB',
            'gender' => 'Perempuan',
        ]);

        $teacher3 = User::create([
            'name' => 'Mr. John Smith, S.Pd',
            'email' => 'john@sman1.sch.id',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'bio' => 'Native Speaker English Teacher',
            'gender' => 'Laki-laki',
        ]);

        // === 3. STUDENTS ===
        $students = [];
        for ($i = 1; $i <= 15; $i++) {
            $students[] = User::create([
                'name' => 'Siswa Teladan ' . $i,
                'email' => 'siswa' . $i . '@sman1.sch.id',
                'password' => Hash::make('password'),
                'role' => 'student',
                'nis' => '1000' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'gender' => $i % 2 == 0 ? 'Perempuan' : 'Laki-laki',
                'exp' => rand(100, 1000),
                'level' => rand(1, 10),
            ]);
        }

        // === 4. COURSES ===
        $courses = [
            Course::create([
                'teacher_id' => $teacher1->id,
                'title' => 'Matematika Peminatan Kelas X MIA',
                'description' => 'Mata pelajaran matematika materi eksponensial, logaritma, dan trigonometri untuk siswa SMA kelas X jurusan MIA.',
            ]),
            Course::create([
                'teacher_id' => $teacher2->id,
                'title' => 'Fisika Kuantum & Dasar Kelas X',
                'description' => 'Mempelajari konsep dasar besaran, satuan, gerak lurus, dan Hukum Newton secara mendalam.',
            ]),
            Course::create([
                'teacher_id' => $teacher3->id,
                'title' => 'Advanced English Communication',
                'description' => 'A course dedicated to improving speaking and listening skills using modern pedagogy.',
            ])
        ];

        // === 5. ENROLL STUDENTS ===
        foreach ($students as $student) {
            foreach ($courses as $course) {
                DB::table('course_students')->insert([
                    'course_id' => $course->id, 
                    'student_id' => $student->id, 
                    'created_at' => now(), 
                    'updated_at' => now()
                ]);
            }
        }

        // === 6. POPULATE EACH COURSE ===
        foreach ($courses as $index => $course) {
            
            // A. Grade Components
            $compTugas = GradeComponent::create(['course_id' => $course->id, 'name' => 'Tugas Harian', 'weight' => 40]);
            $compKuis = GradeComponent::create(['course_id' => $course->id, 'name' => 'Kuis & Ujian', 'weight' => 60]);

            // B. Materials
            for ($m = 1; $m <= 3; $m++) {
                Material::create([
                    'course_id' => $course->id,
                    'title' => 'Materi Pembelajaran Bab ' . $m,
                    'type' => $m % 2 == 0 ? 'pdf' : 'video',
                    'video_link' => $m % 2 != 0 ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : null,
                ]);
            }

            // C. Assignments
            for ($a = 1; $a <= 2; $a++) {
                $asgn = Assignment::create([
                    'course_id' => $course->id,
                    'component_id' => $compTugas->id,
                    'title' => 'Tugas Praktikum / Makalah ' . $a,
                    'description' => 'Kerjakan dengan teliti dan kumpulkan dalam format PDF sebelum deadline.',
                    'deadline' => Carbon::now()->addDays(rand(-5, 5)), // Some overdue, some active
                ]);

                // Map assignment to the 'Tugas Harian' component (Optional depending on DB config, but Service relies on ID grouping)
                // Generate Submissions for 80% of students
                foreach ($students as $student) {
                    if (rand(1, 100) <= 80) {
                        $score = rand(60, 100);
                        \App\Models\Submission::create([
                            'assignment_id' => $asgn->id,
                            'student_id' => $student->id,
                            'file_path' => 'dummy_submission.pdf',
                            'score' => $score,
                            'feedback' => $score > 85 ? 'Sangat Bagus!' : 'Tingkatkan lagi belajarnya.',
                        ]);
                    }
                }
            }

            // D. Quizzes
            $quiz = Quiz::create([
                'course_id' => $course->id,
                'component_id' => $compKuis->id,
                'title' => 'Ujian Tengah Semester (UTS)',
                'description' => 'Waktu pengerjaan 60 menit. Dilarang bekerja sama.',
                'duration' => 60,
                'start_time' => Carbon::now()->subDays(2),
                'end_time' => Carbon::now()->addDays(5),
            ]);

            // Quiz Questions
            for ($q = 1; $q <= 5; $q++) {
                $question = QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => 'Soal Pilihan Ganda Nomor ' . $q . ' untuk ' . $course->title,
                    'type' => 'multiple_choice',
                    'points' => 20,
                ]);
                QuizQuestionOption::create(['quiz_question_id' => $question->id, 'option_text' => 'Pilihan Salah A', 'is_correct' => false]);
                QuizQuestionOption::create(['quiz_question_id' => $question->id, 'option_text' => 'Pilihan Benar', 'is_correct' => true]);
                QuizQuestionOption::create(['quiz_question_id' => $question->id, 'option_text' => 'Pilihan Salah B', 'is_correct' => false]);
            }

            // Generate Quiz Attempts
            foreach ($students as $student) {
                if (rand(1, 100) <= 90) { // 90% took the quiz
                    \App\Models\QuizAttempt::create([
                        'quiz_id' => $quiz->id,
                        'student_id' => $student->id,
                        'score' => rand(40, 100),
                        'start_time' => Carbon::now()->subMinutes(60),
                        'end_time' => Carbon::now(),
                    ]);
                }
            }

            // E. Attendances (Simulate 3 days of classes)
            for ($d = 1; $d <= 3; $d++) {
                $attDate = Carbon::today()->subDays($d);
                $att = Attendance::create([
                    'course_id' => $course->id,
                    'title' => 'Pertemuan Ke-' . $d,
                    'date' => $attDate,
                    'start_time' => '07:00:00',
                    'end_time' => '09:00:00',
                    'password' => 'HADIR' . $d,
                ]);

                foreach ($students as $student) {
                    $statuses = ['hadir', 'hadir', 'hadir', 'telat', 'tidak_hadir', 'izin']; 
                    $status = $statuses[array_rand($statuses)];
                    
                    \App\Models\AttendanceStudent::create([
                        'attendance_id' => $att->id,
                        'student_id' => $student->id,
                        'status' => $status,
                    ]);
                }
            }

            // F. Sync Final Grades for Gradebook (Simulate past manual overrides)
            foreach ($students as $student) {
                \App\Models\StudentGrade::updateOrCreate(
                    ['student_id' => $student->id, 'component_id' => $compTugas->id],
                    ['score' => rand(50, 100)]
                );
                \App\Models\StudentGrade::updateOrCreate(
                    ['student_id' => $student->id, 'component_id' => $compKuis->id],
                    ['score' => rand(50, 100)]
                );
            }
        }
        
        // === 7. NOTIFICATIONS ===
        foreach ($students as $student) {
            Notification::create([
                'user_id' => $student->id,
                'title' => 'Selamat Datang di EduLearn LMS!',
                'message' => 'Akun Anda berhasil dibuat. Silakan jelajahi mata pelajaran yang tersedia.',
                'type' => 'system',
                'is_read' => false,
            ]);
        }
    }
}
