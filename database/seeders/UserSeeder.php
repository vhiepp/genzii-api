<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 100;
        for ($i = 1; $i <= 1; $i++) {
            User::factory($count)->create();
            echo "User đã tạo: " . $i * $count . "\n";
        }
        echo "Đã tạo user. \n";
    }
}
