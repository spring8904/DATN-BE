<?php

namespace App\Imports;

use App\Models\Quiz;
use App\Traits\LoggableTrait;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuizImport implements ToModel, WithHeadingRow
{
    use LoggableTrait;

    protected $quizId;

    public function __construct($quizId)
    {
        $this->quizId = $quizId;
    }

    public function model(array $row)
    {
        try {
            DB::beginTransaction();

            if (empty($row['question']) || empty($row['answer_type'])) {
                throw new \Exception('Dữ liệu không hợp lệ: thiếu thông tin quan trọng');
            }

            $quiz = Quiz::query()->find($this->quizId);
            if (!$quiz) {
                throw new \Exception('Không tìm thấy bài ôn tập với ID: ' . $this->quizId);
            }

            $answers = array_values(array_filter([
                $row['answer1'] ?? null,
                $row['answer2'] ?? null,
                $row['answer3'] ?? null,
                $row['answer4'] ?? null,
            ]));

            if (empty($answers)) {
                throw new \Exception('Không có câu trả lời hợp lệ');
            }

            $questionModel = $quiz->questions()->updateOrCreate(
                ['question' => $row['question']],
                [
                    'image' => $row['image'] ?? null,
                    'answer_type' => $row['answer_type'],
                    'description' => $row['description'] ?? null,
                ]
            );

            $questionModel->answers()->delete();

            if ($row['answer_type'] === 'single_choice') {
                $correctAnswerIndex = (int)($row['correct_answer'] ?? -1) - 1;

                if (!isset($answers[$correctAnswerIndex])) {
                    throw new \Exception('Câu trả lời đúng không hợp lệ: ' . $row['correct_answer']);
                }

                foreach ($answers as $index => $answer) {
                    $questionModel->answers()->create([
                        'answer' => $answer,
                        'is_correct' => $index === $correctAnswerIndex,
                    ]);
                }
            } elseif ($row['answer_type'] === 'multiple_choice') {
                $correctAnswers = array_map('trim', explode(',', $row['correct_answer'] ?? ''));
                $correctIndexes = array_map(fn($val) => (int)$val - 1, $correctAnswers);

                foreach ($answers as $index => $answer) {
                    $questionModel->answers()->create([
                        'answer' => $answer,
                        'is_correct' => in_array($index, $correctIndexes),
                    ]);
                }
            } else {
                throw new \Exception('Loại câu hỏi không hợp lệ: ' . $row['answer_type']);
            }

            DB::commit();
            return $questionModel;
        } catch (\Exception $exception) {
            DB::rollBack();

            $this->logError($exception);

            return null;
        }
    }
}
