<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User;
use Mockery\Exception;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Đang tạo user...\n";
        $count = 50;
        $jump = 10;
        $sum = 0;
        for ($i = 1; $i <= $count/$jump; $i++) {
            try {
                User::factory($jump)->create();
                if ($i > 1) echo "\033[F\033[K";
                echo "User đã tạo: " . ($sum += $jump) . "\n";
            } catch (Exception $err) {}
        }
        echo "Đã tạo " . $sum . " user. \n";
        echo "Tạo thất bại " . ($count - $sum) . " user. \n";
    }
}
