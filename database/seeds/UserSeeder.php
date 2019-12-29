<?php declare(strict_types=1);

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = new User(['email' => 'me@kai-sassnowski.com', 'name' => 'Kai']);
        $user->password = bcrypt('password');
        $user->save();
    }
}
