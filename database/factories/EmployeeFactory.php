<?php

namespace Database\Factories;

use App\Models\Prefix;
use App\Models\ZipCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $join_date = $this->faker->dateTimeBetween('-20 years', 'now');
        $birth_date = $this->faker->dateTimeBetween('-60 years', '-20 years');
        $current_date = Carbon::now();

        $age_in_years = $current_date->diffInYears($birth_date);
        $age_in_company_in_years = $current_date->diffInYears($join_date);

        return [
            'username' => $this->faker->unique()->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'middle_initial' => $this->faker->randomLetter,
            'gender' => $this->faker->randomElement(['F', 'M']),
            'email' => $this->faker->unique()->safeEmail,
            'date_of_birth' => $birth_date,
            'time_of_birth' => $this->faker->time(),
            'age_in_years' => $age_in_years,
            'phone_number' => $this->faker->unique()->phoneNumber,
            'place_name' => $this->faker->city,
            'date_of_joining' => $join_date,
            'age_in_company_in_years' => $age_in_company_in_years,
            'prefix_id' => Prefix::factory()->create()->id,
            'zip_code_id' => ZipCode::factory()->create()->id,
        ];
    }
}
