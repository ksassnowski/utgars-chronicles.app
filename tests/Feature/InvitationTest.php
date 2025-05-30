<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Tests\Feature;

use App\History;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/**
 * @internal
 */
final class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function testAcceptInvitation(): void
    {
        $history = History::factory()->create();
        $invitedUser = User::factory()->create();

        $this->actingAs($invitedUser)->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $history->refresh();
        self::assertTrue($history->isPlayer($invitedUser));
    }

    public function testOnlyALoggedInUserCanAcceptAnInvitation(): void
    {
        $history = History::factory()->create();

        $response = $this->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $response->assertRedirect(\route('login'));
    }

    public function testCannotAcceptAnInvitationTwice(): void
    {
        $history = History::factory()->create();
        $invitedUser = User::factory()->create();
        $history->addPlayer($invitedUser);

        $response = $this->actingAs($invitedUser)->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $response->assertSessionHasErrors([
            'invitation' => __('You are already a player in this game'),
        ]);
    }

    public function testOwnerCannotAcceptInvitationToTheirOwnGame(): void
    {
        $history = History::factory()->create();

        $response = $this->actingAs($history->owner)->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $response->assertSessionHasErrors([
            'invitation' => __('You cannot accept an invitation to your own game'),
        ]);
    }

    public function testGuestCanJoinPublicGame(): void
    {
        $history = History::factory()->public()->create();

        $response = $this->post(URL::signedRoute('invitation.accept.guest', ['history' => $history->id]), [
            'name' => '::name::',
        ]);

        $response->assertSessionHas('histories', [$history->id => '::name::']);
    }

    public function testCannotAcceptGuestInvitationAsAuthenticatedUser(): void
    {
        $user = User::factory()->create();
        $history = History::factory()->public()->create();

        $response = $this->actingAs($user)
            ->post(URL::signedRoute('invitation.accept.guest', ['history' => $history->id]), [
                'name' => '::name::',
            ]);

        $response->assertForbidden();
    }

    public function testCannotAcceptGuestInvitationForPrivateGame(): void
    {
        $history = History::factory()->create();

        $response = $this->post(URL::signedRoute('invitation.accept.guest', ['history' => $history->id]), [
            'name' => '::name::',
        ]);

        $response->assertForbidden();
    }
}
