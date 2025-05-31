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

namespace App\MicroscopeEcho\Actions;

use App\CardType;
use App\Event;

interface AddsEcho
{
    public function handle(
        Event $cause,
        Event $event,
        string $name,
        CardType $type,
    ): Event;
}
