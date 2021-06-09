<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Lfg;
use App\User;
use Generator;
use Notification;
use Carbon\Carbon;
use App\LfgRequest;
use Tests\TestCase;
use App\Notifications\NewLfgRequest;
use App\Notifications\LfgRequestWasAccepted;
use App\Notifications\LfgRequestWasRejected;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group Lfg
 */
class LfgRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function send_request_for_upcoming_game(): void
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

    /** @test */
    public function cannot_send_request_if_have_already_send_a_request_for_the_same_game_previously(): void
    {
        $request = LfgRequest::factory()->create();

        $this->actingAs($request->user)
            ->post(route('lfg.requests.store', $request->lfg))
            ->assertSessionHasErrors('lfg', __('You already have a pending request for this game.'));
        self::assertFalse($request->lfg->users->contains($this->user));
    }

    /** @test */
    public function cannot_send_request_to_lfg_with_no_open_slots(): void
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

    /** @test */
    public function cannot_send_request_to_game_that_has_already_started(): void
    {
        $lfg = Lfg::factory()->past()->create();

        $this->login()
            ->post(route('lfg.requests.store', $lfg))
            ->assertSessionHasErrors(['lfg' => 'Game has already happened']);
        self::assertFalse($lfg->users->contains($this->user));
    }

    /** @test */
    public function owner_gets_notified_of_new_requests(): void
    {
        $lfg = Lfg::factory()->create();

        $this->login()
            ->post(route('lfg.requests.store', $lfg));

        Notification::assertSentTo(
            $lfg->owner,
            NewLfgRequest::class,
            fn (NewLfgRequest $notification) => $notification->request->user->is($this->user)
                && $notification->request->lfg->is($lfg)
        );
    }

    /** @test */
    public function user_can_cancel_a_pending_request(): void
    {
        self::markTestIncomplete();
    }

    /** @test */
    public function user_cant_cancel_an_already_rejected_request(): void
    {
        self::markTestIncomplete();
    }

    /** @test */
    public function user_cant_cancel_an_already_accepted_request(): void
    {
        self::markTestIncomplete();
    }

    /** @test */
    public function can_accept_pending_requests(): void
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
            'Expected user to be a player in LFG, but wasn\'t'
        );
        self::assertEquals(now(), $request->fresh()->accepted_at);
    }

    /** @test */
    public function only_owner_can_accept_requests(): void
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
            'Expected user to not be a player in LFG, but was'
        );
        self::assertNull($request->fresh()->accepted_at);
    }

    /**
     * @test
     * @dataProvider notPendingRequestProvider
     */
    public function can_only_accept_pending_requests(callable $createRequest): void
    {
        $lfg = Lfg::factory()->create();
        $request = $createRequest($lfg);

        $this
            ->actingAs($lfg->owner)
            ->post(route('lfg.requests.accept', $request))
            ->assertSessionHasErrors(['request' => 'Can only accept pending requests']);
        Notification::assertNothingSent();
    }

    /** @test */
    public function can_reject_request(): void
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
            'Expected user to not be a player in LFG, but was'
        );
        self::assertEquals(now(), $request->fresh()->rejected_at);
    }

    /**
     * @test
     * @dataProvider notPendingRequestProvider
     */
    public function can_only_reject_pending_requests(callable $createRequest): void
    {
        $lfg = Lfg::factory()->create();
        $request = $createRequest($lfg);

        $this
            ->actingAs($lfg->owner)
            ->post(route('lfg.requests.reject', $request))
            ->assertSessionHasErrors(['request' => 'Can only reject pending requests']);
        Notification::assertNothingSent();
    }

    /** @test */
    public function user_gets_notified_if_request_got_rejected(): void
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
            fn (LfgRequestWasRejected $notification) => $notification->request->is($request)
        );
    }

    /** @test */
    public function user_gets_notified_if_request_got_accepted(): void
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
            fn (LfgRequestWasAccepted $notification) => $notification->request->is($request)
        );
    }

    /** @test */
    public function only_owner_can_reject_requests(): void
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

    /** @test */
    public function delete_all_pending_requests_if_last_slot_was_filled(): void
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

    /** @test */
    public function does_not_delete_other_pending_requests_if_game_isnt_full_yet(): void
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

    /** @test */
    public function user_gets_notified_if_their_request_got_closed_because_the_last_slot_was_filled(): void
    {
        self::markTestIncomplete();
    }

    /** @test */
    public function delete_all_overlapping_requests_after_request_was_accepted(): void
    {
        self::markTestIncomplete();
    }

    public function notPendingRequestProvider(): Generator
    {
        yield from [
            'already accepted request' => [
                fn (Lfg $lfg) => LfgRequest::factory()->for($lfg)->accepted()->create(),
            ],

            'already rejected request' => [
                fn (Lfg $lfg) => LfgRequest::factory()->for($lfg)->rejected()->create(),
            ]
        ];
    }
}
