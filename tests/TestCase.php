<?php

declare(strict_types=1);

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\Assert;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;

/**
 * @internal
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ?User $user = null;

    public function assertActionUsesFormRequest(string $controller, string $method, string $form_request): void
    {
        Assert::assertTrue(\is_subclass_of($form_request, 'Illuminate\\Foundation\\Http\\FormRequest'), $form_request . ' is not a type of Form Request');

        try {
            $reflector = new ReflectionClass($controller);
            $action = $reflector->getMethod($method);
        } catch (ReflectionException $exception) {
            Assert::fail('Controller action could not be found: ' . $controller . '@' . $method);
        }

        Assert::assertTrue($action->isPublic(), 'Action "' . $method . '" is not public, controller actions must be public.');

        $actual = collect($action->getParameters())->contains(static function (ReflectionParameter $parameter) use ($form_request) {
            return $parameter->getType() instanceof ReflectionNamedType && $parameter->getType()->getName() === $form_request;
        });

        Assert::assertTrue($actual, 'Action "' . $method . '" does not have validation using the "' . $form_request . '" Form Request.');
    }

    public function assertRouteUsesMiddleware(string $routeName, array $middlewares, bool $exact = false): void
    {
        $router = resolve(\Illuminate\Routing\Router::class);

        $route = $router->getRoutes()->getByName($routeName);
        $usedMiddlewares = $route->gatherMiddleware();

        Assert::assertNotNull($route, "Unable to find route for name `{$routeName}`");

        if ($exact) {
            $unusedMiddlewares = \array_diff($middlewares, $usedMiddlewares);
            $extraMiddlewares = \array_diff($usedMiddlewares, $middlewares);

            $messages = [];

            if ($extraMiddlewares) {
                $messages[] = 'uses unexpected `' . \implode(', ', $extraMiddlewares) . '` middlware(s)';
            }

            if ($unusedMiddlewares) {
                $messages[] = "doesn't use expected `" . \implode(', ', $unusedMiddlewares) . '` middlware(s)';
            }

            $messages = \implode(' and ', $messages);

            Assert::assertTrue(\count($unusedMiddlewares) + \count($extraMiddlewares) === 0, "Route `{$routeName}` " . $messages);
        } else {
            $unusedMiddlewares = \array_diff($middlewares, $usedMiddlewares);

            Assert::assertTrue(\count($unusedMiddlewares) === 0, "Route `{$routeName}` does not use expected `" . \implode(', ', $unusedMiddlewares) . '` middleware(s)');
        }
    }

    protected function login()
    {
        if (null === $this->user) {
            $this->user = User::factory()->create();
        }

        $this->actingAs($this->user);

        return $this;
    }
}
