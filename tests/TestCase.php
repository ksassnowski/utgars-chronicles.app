<?php declare(strict_types=1);

namespace Tests;

use App\User;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use PHPUnit\Framework\Assert;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ?User $user = null;

    protected function login()
    {
        if ($this->user === null) {
            $this->user = User::factory()->create();
        }

        $this->actingAs($this->user);

        return $this;
    }

    public function assertActionUsesFormRequest(string $controller, string $method, string $form_request)
    {
        Assert::assertTrue(is_subclass_of($form_request, 'Illuminate\\Foundation\\Http\\FormRequest'), $form_request . ' is not a type of Form Request');

        try {
            $reflector = new ReflectionClass($controller);
            $action = $reflector->getMethod($method);
        } catch (ReflectionException $exception) {
            Assert::fail('Controller action could not be found: ' . $controller . '@' . $method);
        }

        Assert::assertTrue($action->isPublic(), 'Action "' . $method . '" is not public, controller actions must be public.');

        $actual = collect($action->getParameters())->contains(function (ReflectionParameter $parameter) use ($form_request) {
            return $parameter->getType() instanceof ReflectionNamedType && $parameter->getType()->getName() === $form_request;
        });

        Assert::assertTrue($actual, 'Action "' . $method . '" does not have validation using the "' . $form_request . '" Form Request.');
    }
}
