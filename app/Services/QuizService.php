<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizAttempt;

class QuizService
{
    /**
     * Menyimpan data kuis baru ke basis data.
     *
     * @param array $data Data kuis yang tervalidasi
     * @return Quiz
     */
    public function createQuiz(array $data): Quiz
    {
        return Quiz::create($data);
    }

    /**
     * Memperbarui rincian kuis yang ada.
     *
     * @param Quiz $quiz Kuis yang diubah
     * @param array $data Data kuis yang tervalidasi
     * @return Quiz
     */
    public function updateQuiz(Quiz $quiz, array $data): Quiz
    {
        $quiz->update($data);
        return $quiz;
    }

    /**
     * Menyimpan dan menyisipkan pertanyaan baru ke dalam kuis.
     *
     * @param Quiz $quiz Kuis tujuan
     * @param array $data Data pertanyaan yang tervalidasi
     * @return void
     */
    public function addQuestion(Quiz $quiz, array $data): void
    {
        $question = $quiz->questions()->create([
            'question_text' => $data['question_text'],
            'type' => $data['type'],
            'points' => $data['points'],
        ]);

        if ($data['type'] === 'multiple_choice' && isset($data['options'])) {
            foreach ($data['options'] as $index => $optionData) {
                if (isset($optionData['text'])) {
                    $question->options()->create([
                        'option_text' => $optionData['text'],
                        'is_correct' => ($index == $data['correct_option']),
                    ]);
                }
            }
        }
    }

    /**
     * Menilai pengerjaan kuis siswa secara manual atau gabungan.
     *
     * @param Quiz $quiz
     * @param QuizAttempt $attempt
     * @param array $pointsData Array poin yang diberikan oleh guru
     * @return float Skor persentase akumulasi kuis
     */
    public function calculateAndSaveGrade(Quiz $quiz, QuizAttempt $attempt, array $pointsData): float
    {
        $totalPointsEarned = 0;
        $totalMaxPoints = $quiz->questions()->sum('points');

        foreach ($attempt->answers as $answer) {
            $question = $answer->question;
            $awarded = $answer->points_awarded; // By default contains previous or auto-grade points

            if ($question && $question->type === 'essay') {
                $awardedData = isset($pointsData['points'][$answer->id]) ? (int)$pointsData['points'][$answer->id] : $answer->points_awarded;
                
                // Pastikan poin tidak melebihi poin maksimal dari pertanyaan tersebut
                $awarded = min($awardedData, $question->points);

                $answer->update([
                    'points_awarded' => $awarded,
                    'is_correct' => $awarded > 0,
                ]);
            }

            $totalPointsEarned += $awarded;
        }

        // Kalkulasi skor akhir skala 1-100
        $score = $totalMaxPoints > 0 ? ($totalPointsEarned / $totalMaxPoints) * 100 : 0;
        $attempt->update(['score' => $score]);

        return $score;
    }
}
