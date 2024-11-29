<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $customers = [];

        for ($i = 0; $i < 600; $i++) {
            // Generate a random date within the last 2 months
            $createdAt = $faker->dateTimeBetween('-2 months', 'now');
            $updatedAt = $faker->dateTimeBetween($createdAt, 'now');

            $customers[] = [
                'name' => $faker->company,
                'active' => $faker->boolean(70), // 70% chance to be active
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }

        // Batch insert all customers
        DB::table('customers')->insert($customers);

        $this->command->info('1000 customers seeded successfully!');
    }
}
