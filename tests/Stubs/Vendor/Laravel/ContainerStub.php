<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\Vendor\Laravel;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;

/**
 * @SuppressWarnings(PHPMD) Laravel interface sucks
 */
class ContainerStub implements Container
{
    /**
     * @var mixed[]
     */
    private $services;

    /**
     * Constructor
     *
     * @param mixed[] $services
     */
    public function __construct(?array $services = null)
    {
        $this->services = $services ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function addContextualBinding($concrete, $abstract, $implementation)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function afterResolving($abstract, ?Closure $callback = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function alias($abstract, $alias)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function bindIf($abstract, $concrete = null, $shared = false)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function bound($abstract)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function call($callback, array $parameters = [], $defaultMethod = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function extend($abstract, Closure $closure)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function factory($abstract)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function instance($abstract, $instance)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function make($abstract, array $parameters = [])
    {
        if (\array_key_exists($abstract, $this->services) === false) {
            throw new BindingResolutionException();
        }

        return $this->services[$abstract];
    }

    /**
     * {@inheritdoc}
     */
    public function resolved($abstract)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function resolving($abstract, ?Closure $callback = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function singleton($abstract, $concrete = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function tag($abstracts, $tags)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function tagged($tag)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function when($concrete)
    {
    }
}
