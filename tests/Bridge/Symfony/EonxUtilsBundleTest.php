<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Bridge\Symfony;

use EoneoPay\Utils\Interfaces\ArrInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Tests\EoneoPay\Utils\Stubs\Services\ServiceStub;
use Tests\EoneoPay\Utils\Stubs\Vendor\Symfony\Component\KernelStub;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Bridge\Symfony\EonxUtilsBundle
 */
class EonxUtilsBundleTest extends TestCase
{
    /**
     * Test that container returns the expected registered service.
     *
     * @return void
     */
    public function testEonxUtilsRegisteredServices(): void
    {
        $container = $this->getContainer();

        $service = $container->get(ServiceStub::class);

        $actualInstance = ($service instanceof ServiceStub) === true ? $service->getArr() : null;
        self::assertInstanceOf(ArrInterface::class, $actualInstance);
    }

    /**
     * Get container.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private function getContainer(): ContainerInterface
    {
        $kernel = new KernelStub();
        $kernel->boot();
        $container = $kernel->getContainer();

        if (($container instanceof ContainerInterface) !== true) {
            throw new RuntimeException('Unable to build container.');
        }

        /**
         * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
         *
         * @see https://youtrack.jetbrains.com/issue/WI-37859 - typehint required until PhpStorm recognises === check
         */
        return $container;
    }
}
