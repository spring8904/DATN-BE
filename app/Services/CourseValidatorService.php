<?php

namespace App\Services;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Video;

class CourseValidatorService
{
    public static function validateCourse(Course $course): array
    {
        $errors = [];

        if (!$course->name
            || !$course->description
            || !$course->thumbnail
            || !$course->level
            || !$course->category_id
        ) {
            $errors[] = 'Khoá học phải có đầy đủ thông tin cơ bản.';
        }

        if (!$course->is_free && (!$course->price || $course->price <= 0)) {
            $errors[] = "Khóa học có phí phải có giá hợp lệ.";
        }

        $benefits = json_decode($course->benefits, true) ?? [];
        $requirements = json_decode($course->requirements, true) ?? [];
        $qa = json_decode($course->qa, true) ?? [];

        if (count($benefits) < 4 || count($benefits) > 10) {
            $errors[] = "Khóa học phải có từ 4 đến 10 lợi ích.";
        }
        if (count($requirements) < 4 || count($requirements) > 10) {
            $errors[] = "Khóa học phải có từ 4 đến 10 yêu cầu.";
        }
        if (count($qa) < 1 || count($qa) > 5) {
            $errors[] = "Khóa học phải có từ 1 đến 5 câu hỏi thường gặp.";
        }

        $chapters = Chapter::query()->where('course_id', $course->id)->get();

        if ($chapters->count() < 5) {
            $errors[] = "Khóa học phải có ít nhất 5 chương học. Hiện tại có {$chapters->count()} chương.";
        }

        foreach ($chapters as $chapter) {
            if (!$chapter->title) {
                $errors[] = "Chương học ID {$chapter->id} không có tiêu đề.";
            }

            $lessons = Lesson::query()->where('chapter_id', $chapter->id)->get();

            if ($lessons->count() < 5) {
                $errors[] = "Chương '{$chapter->title}' cần ít nhất 5 bài giảng. Hiện tại có {$lessons->count()} bài.";
            }

            foreach ($lessons as $lesson) {
                if (!$lesson->title || !$lesson->content) {
                    $errors[] = "Bài giảng '{$lesson->title}' (ID {$lesson->id}) trong chương '{$chapter->title}' thiếu tiêu đề hoặc nội dung.";
                }

                if ($lesson->lessonable_type === Video::class) {
                    $video = Video::query()->find($lesson->lessonable_id);

                    if ($video && $video->duration < 1200) {
                        $errors[] = "Bài giảng '{$lesson->title}' (ID {$lesson->id}) trong chương '{$chapter->title}' có video dưới 20 phút.";
                    }

                    if ($lesson->lessonable_type === Quiz::class) {
                        $quiz = Quiz::query()->find($lesson->lessonable_id);
                        if ($quiz) {
                            $questions = Question::query()->where('quiz_id', $quiz->id)->get();
                            if ($questions->count() < 3 || $questions->count() > 5) {
                                $errors[] = "Bài kiểm tra '{$lesson->title}' (ID {$lesson->id}) trong chương '{$chapter->title}' phải có từ 3 đến 5 câu hỏi. Hiện tại có {$questions->count()} câu.";
                            }
                        }
                    }
                }
            }
        }

        return $errors;
    }
}
