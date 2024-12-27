<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'avatar' => 'upload/avt/default.png',
            'password' => static::$password ??= Hash::make('password'),
            'name' => fake()->firstName(),
            'gender' => fake()->randomElement(['male', 'female']),
            'role_id' => 3,
        ];
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return $this
     */
    public function IT(): self
    {
        return $this->state(fn (): array => ['role_id' => 1]);
    }

    /**
     * Indicate that the user is a teacher.
     *
     * @return $this
     */

    public function teacher(): self
    {
        return $this->state(fn (): array => ['role_id' => 2]);
    }

    /**
     * Indicate that the user is a user.
     *
     * @return $this
     */

    public function class_supervisor(): self
    {
        return $this->state(fn (): array => ['role_id' => 4]);
    }
}
