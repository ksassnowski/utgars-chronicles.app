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

use App\Http\Controllers\FeedbackController;
use App\Http\Requests\SubmitFeedbackRequest;
use App\Notifications\FeedbackSubmitted;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\AuthenticatedRoutesTest;
use Tests\TestCase;
use Tests\ValidateRoutesTest;

/**
 * @internal
 */
final class FeedbackTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoutesTest;
    use ValidateRoutesTest;

    public static function authenticatedRoutesProvider(): \Generator
    {
        yield from [
            'submit feedback' => ['post', '/feedback'],
        ];
    }

    public function testSubmitFeedback(): void
    {
        Notification::fake();
        $adminUser = User::factory()->create(['email' => 'admin@email.com']);
        config(['app.admin_email' => 'admin@email.com']);

        $this->login()->post(route('feedback.submit'), [
            'message' => '::message::',
        ]);

        Notification::assertSentTo(
            [$adminUser],
            FeedbackSubmitted::class,
            function (FeedbackSubmitted $notification) {
                return '::message::' === $notification->message && $notification->user->id === $this->user->id;
            },
        );
    }

    public static function validationProvider(): \Generator
    {
        yield from [
            'submit feedback' => [
                FeedbackController::class,
                '__invoke',
                SubmitFeedbackRequest::class,
            ],
        ];
    }
}
