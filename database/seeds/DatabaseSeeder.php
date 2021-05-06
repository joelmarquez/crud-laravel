<?php

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
        // $this->call(UsersTableSeeder::class);

        // Se crea usuario de manera manual
        App\User::create ([
            'name' => 'Joel Marquez',
            'email' => 'joelmarquez@gmail.com',
            'password' => bcrypt('123456')
        ]);

        // El factory crea los posts
        factory(App\Post::class, 24)->create();
    }
}
