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

use App\Lfg;
use App\LfgRequest;
use App\Notifications\LfgRequestWasAccepted;
use App\Notifications\LfgRequestWasRejected;
use App\Notifications\NewLfgRequest;
use App\User;
use Carbon\Carbon;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Notification;
use Tests\TestCase;

/**
 * @group Lfg
 *
 * @internal
 */
final class LfgRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    public function testSendRequestForUpcomingGame(): void
    {
        $lfg = Lfg::factory()->create();

        $this->login()
            ->withoutExceptionHandling()
            ->post(route('lfg.requests.store', $lfg), [
                'message' => '::message::',
            ])
            ->assertRedirect(route('lfg.requests.index'));

        $this->assertDatabaseHas('lfg_requests', [
            'lfg_id' => $lfg->id,
            'user_id' => $this->user->id,
            'accepted_at' => null,
            'rejected_at' => null,
            'message' => '::message::',
        ]);
    }

    public function testCannotSendRequestIfHaveAlreadySendARequestForTheSameGamePreviously(): void
    {
        $request = LfgRequest::factory()->create();

        $this->actingAs($request->user)
            ->post(route('lfg.requests.store', $request->lfg))
            ->assertSessionHasErrors('lfg', __('You already have a pending request for this game.'));
        self::assertFalse($request->lfg->users->contains($this->user));
    }

    public function testCannotSendRequestToLfgWithNoOpenSlots(): void
    {
        $lfg = Lfg::factory()
            // Only add one extra user since the owner of the lfg
            // is always included
            ->has(User::factory())
            ->create(['slots' => 2]);

        $this->login()
            ->post(route('lfg.requests.store', $lfg))
            ->assertSessionHasErrors(['lfg' => 'Game is already full']);
        self::assertFalse($lfg->users->contains($this->user));
    }

    public function testCannotSendRequestToGameThatHasAlreadyStarted(): void
    {
        $lfg = Lfg::factory()->past()->create();

        $this->login()
            ->post(route('lfg.requests.store', $lfg))
            ->assertSessionHasErrors(['lfg' => 'Game has already happened']);
        self::assertFalse($lfg->users->contains($this->user));
    }

    public function testOwnerGetsNotifiedOfNewRequests(): void
    {
        $lfg = Lfg::factory()->create();

        $this->login()
            ->post(route('lfg.requests.store', $lfg));

        Notification::assertSentTo(
            $lfg->owner,
            NewLfgRequest::class,
            fn (NewLfgRequest $notification) => $notification->request->user->is($this->user)
                && $notification->request->lfg->is($lfg),
        );
    }

    public function testUserCanCancelAPendingRequest(): void
    {
        self::markTestIncomplete();
    }

    public function testUserCantCancelAnAlreadyRejectedRequest(): void
    {
        self::markTestIncomplete();
    }

    public function testUserCantCancelAnAlreadyAcceptedRequest(): void
    {
        self::markTestIncomplete();
    }

    public function testCanAcceptPendingRequests(): void
    {
        Carbon::setTestNow(now()->startOfMinute());
        $lfg = Lfg::factory()->create(['slots' => 2]);
        $request = LfgRequest::factory()
            ->for($lfg)
            ->pending()
            ->create();

        $this
            ->withoutExceptionHandling()
            ->actingAs($lfg->owner)
            ->post(route('lfg.requests.accept', $request));

        self::assertTrue(
            $lfg->users->contains($request->user),
            'Expected user to be a player in LFG, but wasn\'t',
        );
        self::assertEquals(now(), $request->fresh()->accepted_at);
    }

    public function testOnlyOwnerCanAcceptRequests(): void
    {
        $lfg = Lfg::factory()->create(['slots' => 2]);
        $request = LfgRequest::factory()
            ->for($lfg)
            ->pending()
            ->create();

        $this
            ->actingAs(User::factory()->create())
            ->post(route('lfg.requests.accept', $request))
            ->assertForbidden();

        self::assertFalse(
            $lfg->users->contains($request->user),
            'Expected user to not be a player in LFG, but was',
        );
        self::assertNull($request->fresh()->accepted_at);
    }

    /**
     * @dataProvider notPendingRequestProvider
     */
    public function testCanOnlyAcceptPendingRequests(callable $createRequest): void
    {
        $lfg = Lfg::factory()->create();
        $request = $createRequest($lfg);

        $this
            ->actingAs($lfg->owner)
            ->post(route('lfg.requests.accept', $request))
            ->assertSessionHasErrors(['request' => 'Can only accept pending requests']);
        Notification::assertNothingSent();
    }

    public function testCanRejectRequest(): void
    {
        Carbon::setTestNow(now()->startOfMinute());
        $lfg = Lfg::factory()->create(['slots' => 2]);
        $request = LfgRequest::factory()
            ->for($lfg)
            ->pending()
            ->create();

        $this
            ->withoutExceptionHandling()
            ->actingAs($lfg->owner)
            ->post(route('lfg.requests.reject', $request));

        self::assertFalse(
            $lfg->users->contains($request->user),
            'Expected user to not be a player in LFG, but was',
        );
        self::assertEquals(now(), $request->fresh()->rejected_at);
    }

    /**
     * @dataProvider notPendingRequestProvider
     */
    public function testCanOnlyRejectPendingRequests(callable $createRequest): void
    {
        $lfg = Lfg::factory()->create();
        $request = $createRequest($lfg);

        $this
            ->actingAs($lfg->owner)
            ->post(route('lfg.requests.reject', $request))
            ->assertSessionHasErrors(['request' => 'Can only reject pending requests']);
        Notification::assertNothingSent();
    }

    public function testUserGetsNotifiedIfRequestGotRejected(): void
    {
        $lfg = Lfg::factory()->create();
        $request = LfgRequest::factory()
            ->for($lfg)
            ->pending()
            ->create();

        $this
            ->actingAs($lfg->owner)
            ->post(route('lfg.requests.reject', $request));

        Notification::assertSentTo(
            $request->user,
            LfgRequestWasRejected::class,
            static fn (LfgRequestWasRejected $notification) => $notification->request->is($request),
        );
    }

    public function testUserGetsNotifiedIfRequestGotAccepted(): void
    {
        $lfg = Lfg::factory()->create();
        $request = LfgRequest::factory()
            ->for($lfg)
            ->pending()
            ->create();

        $this
            ->actingAs($lfg->owner)
            ->post(route('lfg.requests.accept', $request));

        Notification::assertSentTo(
            $request->user,
            LfgRequestWasAccepted::class,
            static fn (LfgRequestWasAccepted $notification) => $notification->request->is($request),
        );
    }

    public function testOnlyOwnerCanRejectRequests(): void
    {
        $lfg = Lfg::factory()->create(['slots' => 2]);
        $request = LfgRequest::factory()
            ->for($lfg)
            ->pending()
            ->create();

        $this
            ->actingAs(User::factory()->create())
            ->post(route('lfg.requests.reject', $request))
            ->assertForbidden();
        self::assertNull($request->fresh()->rejected_at);
    }

    public function testDeleteAllPendingRequestsIfLastSlotWasFilled(): void
    {
        $lfg = Lfg::factory()->create(['slots' => 2]);
        $requests = LfgRequest::factory()
            ->count(3)
            ->for($lfg)
            ->pending()
            ->create();

        $this->actingAs($lfg->owner)
            ->post(route('lfg.requests.accept', $requests->first()));
        self::assertCount(0, $lfg->fresh()->pendingRequests);
    }

    public function testDoesNotDeleteOtherPendingRequestsIfGameIsntFullYet(): void
    {
        $lfg = Lfg::factory()->create(['slots' => 3]);
        $requests = LfgRequest::factory()
            ->count(3)
            ->for($lfg)
            ->pending()
            ->create();

        $this->actingAs($lfg->owner)
            ->post(route('lfg.requests.accept', $requests->first()));
        self::assertCount(2, $lfg->fresh()->pendingRequests);
    }

    public function testUserGetsNotifiedIfTheirRequestGotClosedBecauseTheLastSlotWasFilled(): void
    {
        self::markTestIncomplete();
    }

    public function testDeleteAllOverlappingRequestsAfterRequestWasAccepted(): void
    {
        self::markTestIncomplete();
    }

    public function notPendingRequestProvider(): Generator
    {
        yield from [
            'already accepted request' => [
                static fn (Lfg $lfg) => LfgRequest::factory()->for($lfg)->accepted()->create(),
            ],

            'already rejected request' => [
                static fn (Lfg $lfg) => LfgRequest::factory()->for($lfg)->rejected()->create(),
            ],
        ];
    }
}
