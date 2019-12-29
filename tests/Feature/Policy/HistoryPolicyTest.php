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
        $owner = factory(User::class)->create();
        $history = factory(History::class)->create(['owner_id' => $owner->id]);

        $policy = new HistoryPolicy();

        $this->assertTrue($policy->modifyGame($owner, $history));
    }

    /** @test */
    public function playerCanModifyGame(): void
    {
        $player = factory(User::class)->create();
        /** @var History $history */
        $history = factory(History::class)->create();
        $history->addPlayer($player);

        $policy = new HistoryPolicy();

        $this->assertTrue($policy->modifyGame($player, $history));
    }

    /** @test */
    public function everyoneElseCantModifyGame()
    {
        $randomUser = factory(User::class)->create();
        $history = factory(History::class)->create();

        $policy = new HistoryPolicy();

        $this->assertFalse($policy->modifyGame($randomUser, $history));
    }
}
