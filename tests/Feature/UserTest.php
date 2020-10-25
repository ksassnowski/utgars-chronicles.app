<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\MicroscopePlayer;

class UserTest extends TestCase
{
    use MicroscopePlayerTest;

    protected function getPlayerInstance(): MicroscopePlayer
    {
        return User::factory()->create();
    }
}
