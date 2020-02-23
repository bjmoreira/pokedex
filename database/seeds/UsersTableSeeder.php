<?php

use Illuminate\Database\Seeder;

use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * Usuario Admin.
         */
        factory(User::class)->create([
            'name'  => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt(123456)
        ]);

        factory(App\User::class, 9)->create()->each(function ($user) {
          $user->save();
        });

    }

    public function down()
    {
        Schema::drop('users');
    }

}
