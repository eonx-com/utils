<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Bridge\Lumen\Resolvers;

use EoneoPay\Utils\Bridge\Lumen\Interfaces\Resolvers\ControllerResolverInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;

/**
 * Resolves a controller callable from a Route.
 */
final class ControllerResolver implements ControllerResolverInterface
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    private $container;

    /**
     * Constructor
     *
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function resolve($route): ?callable
    {
        $uses = $this->getUses($route) ?? '';
        $split = \explode('@', $uses);

        if (\count($split) !== 2) {
            return null;
        }

        $controller = $this->container->make($split[0]);
        $callable = [$controller, $split[1]];

        if (\is_callable($callable) === false) {
            return null;
        }

        return $callable;
    }

    /**
     * Returns the uses statement from the route.
     *
     * @param mixed $route
     *
     * @return string
     */
    private function getUses($route): ?string
    {
        if (($route instanceof Route) === true) {
            /**
             * @var \Illuminate\Routing\Route $route
             *
             * @see https://youtrack.jetbrains.com/issue/WI-37859 - required until PhpStorm recognises === check
             */
            return $route->getAction('uses') ?: null;
        }

        if (\is_array($route) === false) {
            return null;
        }

        return $route[1]['uses'] ?? null;
    }
}
