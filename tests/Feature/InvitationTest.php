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
    public function existingUserAcceptsInvitationToGame(): void
    {
        $history = factory(History::class)->create();
        $invitedUser = factory(User::class)->create([
            'email' => 'test@email.com',
        ]);

        $this->actingAs($invitedUser)->get(URL::signedRoute('invitation.accept', [
            'email' => 'test@email.com',
            'history' => $history->id,
        ]));

        $history->refresh();
        $this->assertTrue($history->isPlayer($invitedUser));
    }

    /** @test */
    public function cannotAcceptAnInvitationTwice(): void
    {
        $history = factory(History::class)->create();
        $invitedUser = factory(User::class)->create([
            'email' => 'test@email.com',
        ]);
        $history->addPlayer($invitedUser);

        $response = $this->actingAs($invitedUser)->get(URL::signedRoute('invitation.accept', [
            'email' => 'test@email.com',
            'history' => $history->id,
        ]));

        $response->assertSessionHasErrors([
            'invitation' => __('You are already a player in this game')
        ]);
    }
}
