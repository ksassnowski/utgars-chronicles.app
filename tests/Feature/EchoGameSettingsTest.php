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

namespace Tests\Feature;

use App\AgentPowers;
use App\EchoGameSettings;
use App\Events\EchoSettingsUpdated;
use App\History;
use App\Http\Controllers\History\UpdateEchoGameSettingsController;
use App\Http\Requests\UpdateEchoGameSettingsRequest;
use Generator;
use Illuminate\Support\Facades\Event;
use Tests\GameRouteTest;
use Tests\TestCase;
use Tests\ValidateRoutesTest;

/**
 * @internal
 */
final class EchoGameSettingsTest extends TestCase
{
    use ValidateRoutesTest;
    use GameRouteTest;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake([EchoSettingsUpdated::class]);
    }

    public function testUpdateEchoSettings(): void
    {
        /** @var History $history */
        $history = History::factory()
            ->echo()
            ->create();
        $settings = $history->echoGameSettings->update([
            'faction_1_name' => '::old-faction-1-name::',
            'faction_1_description' => '::old-faction-1-description::',
            'faction_2_name' => '::old-faction-2-name::',
            'faction_2_description' => '::old-faction-2-description::',
            'agent_powers' => AgentPowers::Omnipotent,
        ]);

        $this
            ->actingAs($history->owner)
            ->putJson(route('history.echo-settings.update', $history), [
                'faction_1_name' => '::new-faction-1-name::',
                'faction_1_description' => '::new-faction-1-description::',
                'faction_2_name' => '::new-faction-2-name::',
                'faction_2_description' => '::new-faction-2-description::',
                'agent_powers' => AgentPowers::Ordinary,
            ]);

        /** @var EchoGameSettings $settings */
        $settings = $history->echoGameSettings->fresh();
        self::assertSame('::new-faction-1-name::', $settings->faction_1_name);
        self::assertSame('::new-faction-1-description::', $settings->faction_1_description);
        self::assertSame('::new-faction-2-name::', $settings->faction_2_name);
        self::assertSame('::new-faction-2-description::', $settings->faction_2_description);
        self::assertSame(AgentPowers::Ordinary, $settings->agent_powers);
    }

    public function testDispatchEventAfterSettingsWereUpdated(): void
    {
        /** @var History $history */
        $history = History::factory()
            ->echo()
            ->create();

        $this
            ->actingAs($history->owner)
            ->putJson(route('history.echo-settings.update', $history), [
                'faction_1_name' => '::faction-1-name::',
                'faction_1_description' => '::faction-1-description::',
                'faction_2_name' => '::faction-2-name::',
                'faction_2_description' => '::faction-2-description::',
                'agent_powers' => AgentPowers::Ordinary,
            ]);

        Event::assertDispatched(
            EchoSettingsUpdated::class,
            static fn (EchoSettingsUpdated $event): bool => $event->history->is($history),
        );
    }

    public function gameRouteProvider(): Generator
    {
        yield ['history.echo-settings.update'];
    }

    public function validationProvider(): Generator
    {
        yield from [
            'update echo settings' => [
                UpdateEchoGameSettingsController::class,
                '__invoke',
                UpdateEchoGameSettingsRequest::class,
            ],
        ];
    }
}
