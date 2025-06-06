<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // UsersTableSeeder::class,
        // AddressesTableSeeder::class,
        // CategoriesTableSeeder::class,
        // ItemsTableSeeder::class,
        // CategoryItemTableSeeder::class,
        // ItemImagesTableSeeder::class,
        // OrdersTableSeeder::class,
        // CommentsTableSeeder::class,
        // LikesTableSeeder::class,
        $this->call([
            UsersTableSeeder::class,
            AddressesTableSeeder::class,
            CategoriesTableSeeder::class,
            ItemsTableSeeder::class,
            ItemImagesTableSeeder::class,
            CategoryItemTableSeeder::class,
            OrdersTableSeeder::class,
            CommentsTableSeeder::class,
            LikesTableSeeder::class,

        ]);
    }
}
