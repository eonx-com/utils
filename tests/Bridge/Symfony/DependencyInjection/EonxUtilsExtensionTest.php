<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Bridge\Symfony\DependencyInjection;

use EoneoPay\Utils\AnnotationReader;
use EoneoPay\Utils\Arr;
use EoneoPay\Utils\Bridge\Symfony\DependencyInjection\EonxUtilsExtension;
use EoneoPay\Utils\CheckDigit;
use EoneoPay\Utils\Collection;
use EoneoPay\Utils\Generator;
use EoneoPay\Utils\Hasher;
use EoneoPay\Utils\Interfaces\AnnotationReaderInterface;
use EoneoPay\Utils\Interfaces\ArrInterface;
use EoneoPay\Utils\Interfaces\CheckDigitInterface;
use EoneoPay\Utils\Interfaces\CollectionInterface;
use EoneoPay\Utils\Interfaces\GeneratorInterface;
use EoneoPay\Utils\Interfaces\HasherInterface;
use EoneoPay\Utils\Interfaces\LuhnInterface;
use EoneoPay\Utils\Interfaces\MathInterface;
use EoneoPay\Utils\Interfaces\PermissionsInterface;
use EoneoPay\Utils\Interfaces\StrInterface;
use EoneoPay\Utils\Interfaces\XmlConverterInterface;
use EoneoPay\Utils\Luhn;
use EoneoPay\Utils\Math;
use EoneoPay\Utils\Permissions;
use EoneoPay\Utils\Str;
use EoneoPay\Utils\XmlConverter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Bridge\Symfony\DependencyInjection\EonxUtilsExtension
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) High coupling required to test all services are loaded.
 */
class EonxUtilsExtensionTest extends TestCase
{
    /**
     * Test that extension loads expected service.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function testLoad(): void
    {
        $builder = new ContainerBuilder();
        $extension = new EonxUtilsExtension();
        $extension->load([], $builder);

        self::assertInstanceOf(AnnotationReader::class, $builder->get(AnnotationReaderInterface::class));
        self::assertInstanceOf(Arr::class, $builder->get(ArrInterface::class));
        self::assertInstanceOf(CheckDigit::class, $builder->get(CheckDigitInterface::class));
        self::assertInstanceOf(Collection::class, $builder->get(CollectionInterface::class));
        self::assertInstanceOf(Generator::class, $builder->get(GeneratorInterface::class));
        self::assertInstanceOf(Hasher::class, $builder->get(HasherInterface::class));
        self::assertInstanceOf(Luhn::class, $builder->get(LuhnInterface::class));
        self::assertInstanceOf(Math::class, $builder->get(MathInterface::class));
        self::assertInstanceOf(Permissions::class, $builder->get(PermissionsInterface::class));
        self::assertInstanceOf(Str::class, $builder->get(StrInterface::class));
        self::assertInstanceOf(XmlConverter::class, $builder->get(XmlConverterInterface::class));
    }
}
