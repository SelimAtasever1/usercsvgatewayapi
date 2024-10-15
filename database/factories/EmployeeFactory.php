<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'emp_id' => $this->faker->unique()->numerify('EMP###'),
            'name_prefix' => $this->faker->title,
            'first_name' => $this->faker->firstName,
            'middle_initial' => $this->faker->randomLetter,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'email' => $this->faker->unique()->safeEmail,
            'date_of_birth' => $this->faker->date(),
            'time_of_birth' => $this->faker->time(),
            'age_in_yrs' => $this->faker->numberBetween(20, 60), 
            'date_of_joining' => $this->faker->date(),
            'age_in_company_years' => $this->faker->randomFloat(1, 1, 40), 
            'phone_no' => $this->faker->phoneNumber,
            'place_name' => $this->faker->city,
            'county' => $this->faker->state,
            'city' => $this->faker->city,
            'zip' => $this->faker->postcode,
            'region' => $this->faker->stateAbbr, 
            'user_name' => $this->faker->unique()->userName
        ];
    }
}
