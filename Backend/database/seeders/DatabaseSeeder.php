<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Question;
use App\Models\Choice;
use App\Models\Assignment;
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

        User::factory()->admin()->count(1)->create();
        User::factory()->teacher()->count(4)->create();
        User::factory()->count(10)->create();

        foreach ($data['class'] as $row) {
            Classes::create($row);
        }

        foreach ($data['enrollment'] as $row) {
            Enrollment::create($row);
        }

        foreach ($data['assignment'] as $row) {
            Assignment::create($row);
        }

        foreach ($data['question'] as $row) {
            $question = Question::create([
                'assignment_id' => $row['assignment_id'],
                'title' => $row['title'],
                'description' => $row['description'],
            ]);
            foreach ($row['choices'] as $choice) {
                Choice::create([
                    'question_id' => $question->id,
                    'choice' => $choice['choice'],
                ]);
            }
        }
    }
}
