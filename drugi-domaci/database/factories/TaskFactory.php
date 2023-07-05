<?php

namespace Database\Factories;

use App\Models\ToDoList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'to_do_list_id' => ToDoList::factory(),
            'description' => fake()->text(),
            'due_date' => fake()->boolean(75) ? fake()->dateTimeBetween('now', '+5 years') : null, //75% chance of having due date, not all tasks have due date
            'done' => fake()->randomElement(['true', 'false']),
        ];
    }
}
