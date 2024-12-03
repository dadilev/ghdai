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
        $deleteOld = DB::table('customers')->delete();
        $customers = [];


        // Seed active subscribers created more than 30 days ago
        for ($i = 0; $i < 70; $i++) {
            $created_at = $faker->dateTimeBetween('-60 days', '-31 days');
            $churned_at = rand(0, 1) ? $faker->dateTimeBetween('-30 days', 'now') : null;

            $customers[] = [
                'id' => $i + 1,
                'name' => $faker->name,
                'mrr' => $churned_at ? 0 : $faker->randomFloat(2, 10, 100),
                'created_at' => $created_at,
                'churned_at' => $churned_at,
            ];
        }

        // Seed new subscribers created in the past 30 days
        for ($i = 70; $i < 100; $i++) {
            $created_at = $faker->dateTimeBetween('-30 days', 'now');
            $churned_at = rand(0, 1) ? $faker->dateTimeBetween($created_at, 'now') : null;

            $customers[] = [
                'id' => $i + 1,
                'name' => $faker->name,
                'mrr' => $churned_at ? 0 : $faker->randomFloat(2, 10, 100),
                'created_at' => $created_at,
                'churned_at' => $churned_at,
            ];
        }

        // Batch insert all customers
        DB::table('customers')->insert($customers);

        $this->command->info('1000 customers seeded successfully!');
    }
}
