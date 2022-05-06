<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\AnonymousPlayer;
use App\History;
use App\MicroscopePlayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 */
final class AnonymousPlayerTest extends TestCase
{
    use RefreshDatabase;
    use MicroscopePlayerTest;

    public function testCanRetrievePlayerNameForHistory(): void
    {
        $history = History::factory()->create();
        $guest = new AnonymousPlayer('::id::', [
            $history->id => '::name::',
        ]);

        self::assertEquals('::name:: (guest)', $guest->getName($history));
    }

    public function testCanHaveDifferentNamesForEachHistory(): void
    {
        [$history1, $history2] = History::factory(2)->create();
        $guest = new AnonymousPlayer('::id::', [
            $history1->id => '::history-1-name::',
            $history2->id => '::history-2-name::',
        ]);

        self::assertEquals('::history-1-name:: (guest)', $guest->getName($history1));
        self::assertEquals('::history-2-name:: (guest)', $guest->getName($history2));
    }

    public function testReturnsItsId(): void
    {
        $guest = new AnonymousPlayer('::id::');

        self::assertEquals('::id::', $guest->getAuthIdentifier());
    }

    public function testJoiningGameTwiceWillOverrideExistingName(): void
    {
        $history = History::factory()->create();
        $guest = new AnonymousPlayer('::id::');

        $guest->joinGame($history, '::old-name::');
        $guest->joinGame($history, '::new-name::');

        self::assertEquals('::new-name:: (guest)', $guest->getName($history));
    }

    protected function getPlayerInstance(): MicroscopePlayer
    {
        return new AnonymousPlayer('::id::', []);
    }
}
