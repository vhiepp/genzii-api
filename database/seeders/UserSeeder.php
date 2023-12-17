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
        $count = 5000;
        $jump = 50;
        $sum = 0;
        for ($i = 1; $i <= $count/$jump; $i++) {
            try {
               $users = User::factory($jump)->create();
               foreach ($users as $user) {
                   $user->accounts()->create([
                       'username' => $user->email,
                       'password' => '123',
                       'provider' => 'email/password',
                       'provider_id' => $user->email,
                   ]);
               }
                if ($i > 1) echo "\033[F\033[K";
                echo "User đã tạo: " . ($sum += $jump) . "\n";
            } catch (Exception $err) {}
        }
        echo "Đã tạo " . $sum . " user. \n";
        echo "Tạo thất bại " . ($count - $sum) . " user. \n";
    }
}
