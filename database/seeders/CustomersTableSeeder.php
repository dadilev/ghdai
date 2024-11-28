<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('customers')->insert([
            ['name' => 'Customer A', 'active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Customer B', 'active' => false, 'created_at' => $now->subMonth(), 'updated_at' => $now],
            ['name' => 'Customer C', 'active' => false, 'created_at' => $now->subMonth(), 'updated_at' => $now->subMonth()],
            ['name' => 'Customer D', 'active' => true, 'created_at' => $now->subMonth(), 'updated_at' => $now],
        ]);
    }
}
