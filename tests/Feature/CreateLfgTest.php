<?php declare(strict_types=1);

namespace Tests\Feature;

use Generator;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group Lfg
 */
class CreateLfgTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_new_lfg_for_a_user(): void
    {
        Carbon::setTestNow(now());
        $startDate = now()->addDays(2);

        $this
            ->login()
            ->post(route('lfg.store'), [
                'title' => '::title::',
                'slots' => 4,
                'start_date' => $startDate,
            ]);

        $this->assertDatabaseHas('lfgs', [
            'user_id' => $this->user->id,
            'title' => '::title::',
            'slots' => 4,
            'start_date' => $startDate,
        ]);
    }

    /**
     * @test
     * @dataProvider validationProvider
     */
    public function validation_tests(array $payload, string $expectedErrorKey): void
    {
        $this->login()
            ->post(route('lfg.store'), $payload)
            ->assertSessionHasErrors($expectedErrorKey);
    }

    public function validationProvider(): Generator
    {
        $validPayload = [
            'title' => '::title::',
            'slots' => 4,
            'start_date' => now()->addDay(),
        ];

        yield from [
            'start_date missing' => [
                Arr::except($validPayload, 'start_date'),
                'start_date',
            ],

            'start_date in past' => [
                array_merge($validPayload, ['start_date' => now()->subDay()]),
                'start_date',
            ],

            'start_date not a valid date' => [
                array_merge($validPayload, ['start_date' => '::not-a-date::']),
                'start_date',
            ],

            'slots missing' => [
                Arr::except($validPayload, 'slots'),
                'slots',
            ],

            'slots less than 2' => [
                array_merge($validPayload, ['slots' => 1]),
                'slots',
            ],

            'title missing' => [
                Arr::except($validPayload, 'title'),
                'title',
            ],

            'title too short' => [
                array_merge($validPayload, ['title' => Str::repeat('a', 4)]),
                'title',
            ],

            'title too long' => [
                array_merge($validPayload, ['title' => Str::repeat('a', 256)]),
                'title',
            ],
        ];
    }
}
