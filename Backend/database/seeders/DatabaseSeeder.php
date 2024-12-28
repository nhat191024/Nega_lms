<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\QuizPackage;
use App\Models\QuizPackageCategory;
use App\Models\Quiz;
use App\Models\Choice;
use App\Models\Classes;
use App\Models\ClassCategory;
use App\Models\ClassAssignment;
use App\Models\AssignmentQuiz;
use App\Models\Enrollment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $json = file_get_contents(base_path('database/seeders/data.json'));
        $data = json_decode($json, true);

        foreach ($data['role'] as $row) {
            Role::create($row);
        }

        User::factory()->class_supervisor()->count(1)->create();
        User::factory()->teacher()->count(1)->create();
        User::factory()->IT()->count(1)->create();
        User::factory()->count(10)->create();

        foreach ($data['category'] as $row) {
            Category::create($row);
        }

        foreach ($data['quiz_package'] as $row) {
            $quizPackage = QuizPackage::create([
                'creator_id' => $row['creator_id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'status' => $row['status'],
                'quiz_id_range' => $row['quiz_id_range'],
            ]);

            foreach ($row['categories'] as $category) {
                QuizPackageCategory::create([
                    'quiz_package_id' => $quizPackage->id,
                    'category_id' => $category['category_id'],
                ]);
            }

            foreach ($row['quizzes'] as $quiz) {
                $quiz = Quiz::create([
                    'quiz_package_id' => $quizPackage->id,
                    'question' => $quiz['question'],
                ]);

                foreach ($quiz['choices'] as $choice) {
                    Choice::create([
                        'quiz_id' => $quiz->id,
                        'choice' => $choice['choice'],
                        'is_correct' => $choice['is_correct'],
                    ]);
                }
            }
        }

        foreach ($data['class'] as $row) {
            $class = Classes::create([
                'code' => $row['code'],
                'name' => $row['name'],
                'description' => $row['description'],
                'teacher_id' => $row['teacher_id'],
                'status' => $row['status'],
            ]);

            foreach ($row['categories'] as $category) {
                ClassCategory::create([
                    'class_id' => $class->id,
                    'category_id' => $category['category_id'],
                ]);
            }

            foreach ($row['assignments'] as $assignment) {
                ClassAssignment::create([
                    'class_id' => $class->id,
                    'type' => $assignment['type'],
                    'title' => $assignment['title'],
                    'description' => $assignment['description'],
                    'duration' => $assignment['duration'],
                    'start_date' => $assignment['start_datetime'],
                    'due_date' => $assignment['due_datetime'],
                    'status' => $assignment['status'],
                ]);
                if ($assignment['type'] === 'quiz') {
                    foreach ($assignment['quiz'] as $quiz) {
                        AssignmentQuiz::create([
                            'assignment_id' => $class->id,
                            'quiz_id' => $quiz['quiz_id'],
                        ]);
                    }
                }
            }
        }

        foreach ($data['enrollment'] as $row) {
            Enrollment::create([
                'class_id' => $row['class_id'],
                'student_id' => $row['student_id'],
            ]);
        }
    }
}
