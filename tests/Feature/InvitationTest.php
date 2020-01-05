<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use App\History;
use Tests\TestCase;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function acceptInvitation(): void
    {
        $history = factory(History::class)->create();
        $invitedUser = factory(User::class)->create();

        $this->actingAs($invitedUser)->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $history->refresh();
        $this->assertTrue($history->isPlayer($invitedUser));
    }

    /** @test */
    public function onlyALoggedInUserCanAcceptAnInvitation(): void
    {
        $history = factory(History::class)->create();

        $response = $this->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function cannotAcceptAnInvitationTwice(): void
    {
        $history = factory(History::class)->create();
        $invitedUser = factory(User::class)->create();
        $history->addPlayer($invitedUser);

        $response = $this->actingAs($invitedUser)->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $response->assertSessionHasErrors([
            'invitation' => __('You are already a player in this game')
        ]);
    }

    /** @test */
    public function ownerCannotAcceptInvitationToTheirOwnGame(): void
    {
        $history = factory(History::class)->create();

        $response = $this->actingAs($history->owner)->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $response->assertSessionHasErrors([
            'invitation' => __('You cannot accept an invitation to your own game')
        ]);
    }
}
