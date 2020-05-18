<?php declare(strict_types=1);

namespace Tests\Feature\Rules;

use App\Event;
use App\Period;
use App\History;
use Tests\TestCase;
use App\Rules\ValidPosition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidPositionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canOnlyInsertIntoPositionOneIfBoardIsEmpty(): void
    {
        $history = factory(History::class)->create();
        $rule = new ValidPosition('periods', 'history_id', $history->id);

        $this->assertTrue($rule->passes('position', 1));
        $this->assertFalse($rule->passes('position', 2));
    }

    /** @test */
    public function cannotBeLargerThanTheMaxPositionPlusOne(): void
    {
        $period = factory(Period::class)->create(['position' => 1]);
        $rule = new ValidPosition('periods', 'history_id', $period->history_id);

        $this->assertFalse($rule->passes('position', 3));
    }

    /** @test */
    public function onlyLooksAtTheMaxPositionOfTheSameHistory(): void
    {
        factory(Period::class)->create([
            'position' => 2,
            'history_id' => 1,
        ]);
        factory(Period::class)->create([
            'position' => 1,
            'history_id' => 2,
        ]);

        $rule = new ValidPosition('periods', 'history_id', 1);

        $this->assertTrue($rule->passes('position', 3));
    }

    /** @test */
    public function worksForDifferentEntities(): void
    {
        factory(Event::class)->create([
            'position' => 2,
            'period_id' => 1,
        ]);
        factory(Event::class)->create([
            'position' => 1,
            'period_id' => 2,
        ]);

        $rule = new ValidPosition('events', 'period_id', 1);

        $this->assertTrue($rule->passes('position', 3));
    }
}
