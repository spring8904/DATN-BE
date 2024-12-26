<?php

namespace App\Imports;

use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\Quizz;
use App\Traits\LoggableTrait;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class QuizImport implements ToModel, WithHeadingRow
{
    use LoggableTrait;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            DB::beginTransaction();

            if (empty($row['question']) || empty($row['answer_type'])) {
                throw new \Exception('Question or answer type is missing.');
            }


            $answers = [
                $row['answer1'],
                $row['answer2'],
                $row['answer3'],
                $row['answer4']
            ];

            if (in_array(null, $answers, true)) {
                throw new \Exception('All answers must have content.');
            }

            if ($row['answer_type'] === 'one_choice') {
                $correctAnswerIndex = (int)$row['correct_answer'] - 1;
                if (!isset($answers[$correctAnswerIndex])) {
                    throw new \Exception('Correct answer index is invalid for single choice.');
                }
            } elseif ($row['answer_type'] === 'multiple_choice') {
                $correctAnswerIndices = explode(',', $row['correct_answer']);
                foreach ($correctAnswerIndices as $index) {
                    $index = (int)trim($index) - 1;
                    if (!isset($answers[$index])) {
                        throw new \Exception('Correct answer index is invalid for multiple choice.');
                    }
                }
            } else {
                throw new \Exception('Invalid answer type.');
            }

            $quiz = Quiz::query()->create([
                'question' => $row['question'],
                'image' => $row['image'] ?? "",
                'answer_type' => $row['answer_type'],
                'description' => $row['description'] ?? "",
            ]);

            foreach ($answers as $index  => $answer) {
                QuizAnswer::create([
                    'quiz_id' => $quiz->id,
                    'answer' => $answer,
                    'is_correct' => ($row['answer_type'] === 'one_choice')
                        ? ($index === $correctAnswerIndex ? 1 : 0)
                        : (in_array($index, array_map(fn($i) => (int)$i - 1, explode(',', $row['correct_answer'])), true) ? 1 : 0),
                ]);
            }

            DB::commit();

            return $quiz;
        } catch (\Exception $e) {

            DB::rollBack();

            $this->logError($e);

            return null;
        }
    }
}
