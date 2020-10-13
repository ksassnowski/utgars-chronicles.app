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
        $history = History::factory()->create();
        $rule = new ValidPosition('periods', 'history_id', $history->id);

        $this->assertTrue($rule->passes('position', 1));
        $this->assertFalse($rule->passes('position', 2));
    }

    /** @test */
    public function cannotBeLargerThanTheMaxPositionPlusOne(): void
    {
        $period = Period::factory()->create(['position' => 1]);
        $rule = new ValidPosition('periods', 'history_id', $period->history_id);

        $this->assertFalse($rule->passes('position', 3));
    }

    /** @test */
    public function onlyLooksAtTheMaxPositionOfTheSameHistory(): void
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

        $this->assertTrue($rule->passes('position', 3));
    }

    /** @test */
    public function worksForDifferentEntities(): void
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

        $this->assertTrue($rule->passes('position', 3));
    }
}
