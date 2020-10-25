<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Project;
use App\Models\Reward;
use Illuminate\Database\Eloquent\Factories\Factory;

class RewardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reward::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->randomNumber(),
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->paragraph(),
            'projectId' => $this->faker->randomElement(Project::all()->pluck('id'))
        ];
    }
}
