<?php declare(strict_types=1);

namespace Tests\Feature\Policy;

use App\User;
use App\History;
use Tests\TestCase;
use App\Policies\HistoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HistoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ownerCanModifyGame(): void
    {
        $owner = User::factory()->create();
        $history = History::factory()->create(['owner_id' => $owner->id]);

        $policy = new HistoryPolicy();

        $this->assertTrue($policy->modifyGame($owner, $history));
    }

    /** @test */
    public function playerCanModifyGame(): void
    {
        $player = User::factory()->create();
        /** @var History $history */
        $history = History::factory()->create();
        $history->addPlayer($player);

        $policy = new HistoryPolicy();

        $this->assertTrue($policy->modifyGame($player, $history));
    }

    /** @test */
    public function everyoneElseCantModifyGame()
    {
        $randomUser = User::factory()->create();
        $history = History::factory()->create();

        $policy = new HistoryPolicy();

        $this->assertFalse($policy->modifyGame($randomUser, $history));
    }
}
