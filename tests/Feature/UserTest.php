<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Tests\Feature;

use App\MicroscopePlayer;
use App\User;
use Tests\TestCase;

/**
 * @internal
 */
final class UserTest extends TestCase
{
    use MicroscopePlayerTestSuite;

    protected function getPlayerInstance(): MicroscopePlayer
    {
        return User::factory()->create();
    }
}
