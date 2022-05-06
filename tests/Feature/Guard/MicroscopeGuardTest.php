<?php

declare(strict_types=1);

namespace Tests\Feature\Guard;

use App\AnonymousPlayer;
use App\Guards\MicroscopeGuard;
use App\History;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @internal
 */
final class MicroscopeGuardTest extends TestCase
{
    use RefreshDatabase;

    public function testItReturnsTheAuthenticatedUserIfThereIsOne(): void
    {
        $user = User::factory()->create();
        $request = new Request();
        $request->setUserResolver(static fn () => $user);
        $guard = new MicroscopeGuard();

        self::assertEquals($user, $guard($request));
    }

    public function testReturnsAnAnonymousUserIfNoUserExists(): void
    {
        $request = new Request();
        $request->setUserResolver(static fn () => null);
        $request->setLaravelSession(session());
        $guard = new MicroscopeGuard();

        self::assertInstanceOf(AnonymousPlayer::class, $guard($request));
    }

    public function testItUsesTheSessionIdAsTheAnonymousUsersIdentifier(): void
    {
        $request = new Request();
        $request->setUserResolver(static fn () => null);
        $request->setLaravelSession(session());
        $guard = new MicroscopeGuard();

        $player = $guard($request);

        self::assertEquals(session()->getId(), $player->getAuthIdentifier());
    }

    public function testPopulatesTheGuestsHistoriesFromTheSession(): void
    {
        $history = History::factory()->create();
        $request = new Request();
        $request->setUserResolver(static fn () => null);
        session()->put('histories', [
            $history->id => '::name::',
        ]);
        $request->setLaravelSession(session());
        $guard = new MicroscopeGuard();

        $player = $guard($request);

        self::assertTrue($player->isPlayer($history));
    }
}
