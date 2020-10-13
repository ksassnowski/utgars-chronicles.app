<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Generator;
use Tests\TestCase;
use Tests\ValidateRoutesTest;
use Tests\AuthenticatedRoutesTest;
use App\Notifications\FeedbackSubmitted;
use App\Http\Controllers\FeedbackController;
use App\Http\Requests\SubmitFeedbackRequest;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest, ValidateRoutesTest;

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'submit feedback' => ['post', '/feedback'],
        ];
    }

    /** @test */
    public function submitFeedback(): void
    {
        Notification::fake();
        $adminUser = User::factory()->create(['email' => 'admin@email.com']);
        config(['app.admin_email' => 'admin@email.com']);

        $this->login()->post(route('feedback.submit'), [
            'message' => '::message::'
        ]);

        Notification::assertSentTo(
            [$adminUser],
            FeedbackSubmitted::class,
            function (FeedbackSubmitted $notification) {
                return $notification->message === '::message::' && $notification->user->id === $this->user->id;
            }
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
