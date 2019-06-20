<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Bridge\Lumen\Interfaces\Resolvers;

/**
 * Resolves a controller callable from a Route.
 */
interface ControllerResolverInterface
{
    /**
     * Tries to resolves a callable from the Lumen or Laravel route.
     *
     * @param mixed $route
     *
     * @return callable
     */
    public function resolve($route): ?callable;
}
