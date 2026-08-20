<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    // daftarkan juga user seeder dan juga role seedernya dengan menjalankan
    // kode perintah php artisan db:seed --class=RoleSeeder  
    // kode perintah nya php artisan db:seed --class=UserSeeder 
    public function run(): void
    {
        $this->call([
        RoleSeeder::class,
        UserSeeder::class,
        JenisSeeder::class,
        ProdukSeeder::class,
        PenjualanSeeder::class,
        ]);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
