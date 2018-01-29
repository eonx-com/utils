<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\AnnotationReader;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\AnnotationReaderParentStub;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\TestAnnotationStub;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\AnnotationReaderStub;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\UnusedAnnotationStub;

/**
 * @covers \EoneoPay\Utils\AnnotationReader
 */
class AnnotationReaderTest extends TestCase
{
    /**
     * Ensure annotations can be fetched from a class which uses them
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException
     * @throws \ReflectionException
     */
    public function testAnnotationsCanBeReadFromClass(): void
    {
        $annotations = (new AnnotationReader())->getClassPropertyAnnotation(
            AnnotationReaderParentStub::class,
            TestAnnotationStub::class
        );

        self::assertArrayHasKey('parent', $annotations);
        self::assertObjectHasAttribute('name', $annotations['parent']);
        self::assertObjectHasAttribute('enabled', $annotations['parent']);
        self::assertSame('parent_property', $annotations['parent']->name);
        self::assertTrue($annotations['parent']->enabled);
    }

    /**
     * Ensure annotations can be read recursively through parents and traits
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException
     * @throws \ReflectionException
     */
    public function testAnnotationsCanBeReadRecursivelyFromClass(): void
    {
        $annotations = (new AnnotationReader())->getClassPropertyAnnotation(
            AnnotationReaderStub::class,
            TestAnnotationStub::class
        );

        self::assertArrayHasKey('baseProperty', $annotations);
        self::assertArrayHasKey('parent', $annotations);
        self::assertArrayHasKey('trait', $annotations);
        self::assertArrayHasKey('parentTrait', $annotations);
        self::assertArrayNotHasKey('noAnnotation', $annotations);
    }

    /**
     * Ensure unused annotations return empty array
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException
     * @throws \ReflectionException
     */
    public function testUnusedAnnotationReturnsEmptyArray(): void
    {
        $annotations = (new AnnotationReader())->getClassPropertyAnnotation(
            AnnotationReaderStub::class,
            UnusedAnnotationStub::class
        );

        self::assertEmpty($annotations);
    }
}
