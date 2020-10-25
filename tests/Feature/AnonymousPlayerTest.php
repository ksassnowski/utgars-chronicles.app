<?php declare(strict_types=1);

namespace Tests\Feature;

use App\History;
use Tests\TestCase;
use App\AnonymousPlayer;
use App\MicroscopePlayer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnonymousPlayerTest extends TestCase
{
    use RefreshDatabase, MicroscopePlayerTest;

    /** @test */
    public function canRetrievePlayerNameForHistory(): void
    {
        $history = History::factory()->create();
        $guest = new AnonymousPlayer('::id::', [
            ['id' => $history->id, 'name' => '::name::'],
        ]);

        $this->assertEquals('::name::', $guest->getName($history));
    }

    /** @test */
    public function canHaveDifferentNamesForEachHistory(): void
    {
        [$history1, $history2] = History::factory(2)->create();
        $guest = new AnonymousPlayer('::id::', [
            ['id' => $history1->id, 'name' => '::history-1-name::'],
            ['id' => $history2->id, 'name' => '::history-2-name::'],
        ]);

        $this->assertEquals('::history-1-name::', $guest->getName($history1));
        $this->assertEquals('::history-2-name::', $guest->getName($history2));
    }

    /** @test */
    public function returnsItsId(): void
    {
        $history = History::factory()->create();
        $guest = new AnonymousPlayer('::id::', [
            ['id' => $history->id, 'name' => '::name::'],
        ]);

        $this->assertEquals('::id::', $guest->getAuthIdentifier());
    }

    protected function getPlayerInstance(): MicroscopePlayer
    {
        return new AnonymousPlayer('::id::', []);
    }
}
