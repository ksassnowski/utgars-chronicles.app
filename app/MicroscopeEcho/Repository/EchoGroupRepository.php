<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\MicroscopeEcho\Repository;

use App\Event;

interface EchoGroupRepository
{
    public function getEchoGroup(Event $event): int;

    public function getNextPosition(int $echoGroup): int;
}
