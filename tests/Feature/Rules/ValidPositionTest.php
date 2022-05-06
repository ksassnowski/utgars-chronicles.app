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

namespace Tests\Feature\Rules;

use App\Event;
use App\History;
use App\Period;
use App\Rules\ValidPosition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 */
final class ValidPositionTest extends TestCase
{
    use RefreshDatabase;

    public function testCanOnlyInsertIntoPositionOneIfBoardIsEmpty(): void
    {
        $history = History::factory()->create();
        $rule = new ValidPosition('periods', 'history_id', $history->id);

        self::assertTrue($rule->passes('position', 1));
        self::assertFalse($rule->passes('position', 2));
    }

    public function testCannotBeLargerThanTheMaxPositionPlusOne(): void
    {
        $period = Period::factory()->create(['position' => 1]);
        $rule = new ValidPosition('periods', 'history_id', $period->history_id);

        self::assertFalse($rule->passes('position', 3));
    }

    public function testOnlyLooksAtTheMaxPositionOfTheSameHistory(): void
    {
        $history1 = History::factory()->create();
        $history2 = History::factory()->create();
        Period::factory()->create([
            'position' => 2,
            'history_id' => $history1->id,
        ]);
        Period::factory()->create([
            'position' => 1,
            'history_id' => $history2->id,
        ]);

        $rule = new ValidPosition('periods', 'history_id', $history1->id);

        self::assertTrue($rule->passes('position', 3));
    }

    public function testWorksForDifferentEntities(): void
    {
        $period1 = Period::factory()->create();
        $period2 = Period::factory()->create();
        Event::factory()->create([
            'position' => 2,
            'period_id' => $period1->id,
        ]);
        Event::factory()->create([
            'position' => 1,
            'period_id' => $period2->id,
        ]);

        $rule = new ValidPosition('events', 'period_id', $period1->id);

        self::assertTrue($rule->passes('position', 3));
    }
}
