<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DateTime;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User 1 hinzufügen
        $user = new User();
        $user->firstName = 'Jasmin';
        $user->lastName = 'Proier';
        $user->email = "jasmin@test.at";
        $user->password = bcrypt("1234");
        $user->image = "https://images.unsplash.com/photo-1683480678001-d2b60353b0fb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=726&q=80";
        $user->save();

        //User 2 hinzufügen
        $user2 = new User();
        $user2->firstName = 'Patrick';
        $user2->lastName = 'Zauner';
        $user2->email = "zauner@test.at";
        $user2->password = "1234";
        $user2->image = "https://images.unsplash.com/photo-1683480678001-d2b60353b0fb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=726&q=80";
        $user2->save();

    }
}
