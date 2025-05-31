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

namespace Tests\Feature\MicroscopeEcho\Repository;

use App\Event;
use App\MicroscopeEcho\Repository\DatabaseEchoGroupRepository;
use App\MicroscopeEcho\Repository\EchoGroupRepository;
use Tests\TestCase;

/**
 * @internal
 */
final class DatabaseEchoGroupRepositoryTest extends TestCase
{
    use EchoGroupRepositoryContractTests;

    protected function getRepository(Event ...$events): EchoGroupRepository
    {
        foreach ($events as $event) {
            $event->save();
        }

        return new DatabaseEchoGroupRepository();
    }
}
