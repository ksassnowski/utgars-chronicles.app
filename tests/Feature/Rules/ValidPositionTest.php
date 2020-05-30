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
        $history1 = factory(History::class)->create();
        $history2 = factory(History::class)->create();
        factory(Period::class)->create([
            'position' => 2,
            'history_id' => $history1->id,
        ]);
        factory(Period::class)->create([
            'position' => 1,
            'history_id' => $history2->id,
        ]);

        $rule = new ValidPosition('periods', 'history_id', $history1->id);

        $this->assertTrue($rule->passes('position', 3));
    }

    /** @test */
    public function worksForDifferentEntities(): void
    {
        $period1 = factory(Period::class)->create();
        $period2 = factory(Period::class)->create();
        factory(Event::class)->create([
            'position' => 2,
            'period_id' => $period1->id,
        ]);
        factory(Event::class)->create([
            'position' => 1,
            'period_id' => $period2->id,
        ]);

        $rule = new ValidPosition('events', 'period_id', $period1->id);

        $this->assertTrue($rule->passes('position', 3));
    }
}
