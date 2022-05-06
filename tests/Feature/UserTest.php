<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\MicroscopePlayer;
use App\User;
use Tests\TestCase;

/**
 * @internal
 */
final class UserTest extends TestCase
{
    use MicroscopePlayerTest;

    protected function getPlayerInstance(): MicroscopePlayer
    {
        return User::factory()->create();
    }
}
