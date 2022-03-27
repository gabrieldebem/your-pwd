<?php

namespace Database\Factories;

use App\Models\Password;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;

/**
 * @extends Factory
 */
class PasswordFactory extends Factory
{
    protected $model = Password::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'url' => $this->faker->url(),
            'password' => Crypt::encryptString('password'),
        ];
    }
}
