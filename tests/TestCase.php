<?php declare(strict_types=1);

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ?User $user = null;

    protected function login()
    {
        if ($this->user === null) {
            $this->user = factory(User::class)->create();
        }

        $this->actingAs($this->user);

        return $this;
    }
}
