<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Controllers\FeedbackController;
use App\Http\Requests\SubmitFeedbackRequest;
use App\Notifications\FeedbackSubmitted;
use App\User;
use Generator;
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

    public function authenticatedRoutesProvider(): Generator
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

    public function validationProvider(): Generator
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
