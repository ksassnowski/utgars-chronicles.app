<?php declare(strict_types=1);

namespace Tests\Feature\Guard;

use App\User;
use App\History;
use Tests\TestCase;
use App\AnonymousPlayer;
use Illuminate\Http\Request;
use App\Guards\MicroscopeGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MicroscopeGuardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itReturnsTheAuthenticatedUserIfThereIsOne(): void
    {
        $user = User::factory()->create();
        $request = new Request();
        $request->setUserResolver(fn () => $user);
        $guard = new MicroscopeGuard();

        $this->assertEquals($user, $guard($request));
    }

    /** @test */
    public function returnsAnAnonymousUserIfNoUserExists(): void
    {
        $request = new Request();
        $request->setUserResolver(fn () => null);
        $request->setLaravelSession(session());
        $guard = new MicroscopeGuard();

        $this->assertInstanceOf(AnonymousPlayer::class, $guard($request));
    }

    /** @test */
    public function itUsesTheSessionIdAsTheAnonymousUsersIdentifier(): void
    {
        $request = new Request();
        $request->setUserResolver(fn () => null);
        $request->setLaravelSession(session());
        $guard = new MicroscopeGuard();

        $player = $guard($request);

        $this->assertEquals(session()->getId(), $player->getAuthIdentifier());
    }

    /** @test */
    public function populatesTheGuestsHistoriesFromTheSession(): void
    {
        $history = History::factory()->create();
        $request = new Request();
        $request->setUserResolver(fn () => null);
        session()->put('histories', [
            $history->id => '::name::',
        ]);
        $request->setLaravelSession(session());
        $guard = new MicroscopeGuard();

        $player = $guard($request);

        $this->assertTrue($player->isPlayer($history));
    }
}
