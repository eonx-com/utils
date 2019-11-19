<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class EonxUtilsExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');

        $loader = new XmlFileLoader($container, $fileLocator);
        $loader->load('services.xml');
    }
}
