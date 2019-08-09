<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Bridge\Lumen\Resolvers;

use EoneoPay\Utils\Bridge\Lumen\Resolvers\ControllerResolver;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Route;
use stdClass;
use Tests\EoneoPay\Utils\Stubs\Vendor\Laravel\ContainerStub;
use Tests\EoneoPay\Utils\TestCase;

class ControllerResolverTest extends TestCase
{
    /**
     * Tests a route that references a controller not bound in the container.
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testBadlyConfiguredContainer(): void
    {
        $resolver = new ControllerResolver(new ContainerStub([
            // NopeController has no callable nope() function
            'NopeController' => new stdClass()
        ]));

        $result = $resolver->resolve(new Route('POST', '', ['uses' => 'NopeController@nope']));

        static::assertNull($result);
    }

    /**
     * Tests a laravel route that does not have a uses section.
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testMisconfiguredLaravelRoute(): void
    {
        $resolver = new ControllerResolver(new ContainerStub());

        $result = $resolver->resolve(new Route('POST', '', []));

        static::assertNull($result);
    }

    /**
     * Tests a laravel route with no method to call
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testMisconfiguredLaravelRouteNoMethod(): void
    {
        $resolver = new ControllerResolver(new ContainerStub());

        $result = $resolver->resolve([null, ['uses' => 'NopeController']]);

        static::assertNull($result);
    }

    /**
     * Tests a lumen route that does not have a uses section.
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testMisconfiguredLumenRoute(): void
    {
        $resolver = new ControllerResolver(new ContainerStub());

        $result = $resolver->resolve([]);

        static::assertNull($result);
    }

    /**
     * Tests a route that references a controller not bound in the container.
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testMissingController(): void
    {
        $resolver = new ControllerResolver(new ContainerStub());

        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionCode(0);

        $resolver->resolve(new Route('POST', '', ['uses' => 'NopeController@nope']));
    }

    /**
     * Tests that the controller resolver returns null when an unknown route is provided
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testResolveUnknownRoute(): void
    {
        $resolver = new ControllerResolver(new ContainerStub());

        $result = $resolver->resolve(new stdClass());

        static::assertNull($result);
    }

    /**
     * Tests resolution
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testSuccess(): void
    {
        $container = new ContainerStub();
        $resolver = new ControllerResolver(new ContainerStub([
            'ContainerStub' => $container
        ]));

        $result = $resolver->resolve(new Route('POST', '', ['uses' => 'ContainerStub@flush']));

        static::assertSame([$container, 'flush'], $result);
    }
}
