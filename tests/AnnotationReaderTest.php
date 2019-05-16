<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\AnnotationReader;
use ReflectionMethod;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\AnnotationReaderParentStub;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\AnnotationReaderStub;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\TestAnnotationStub;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\TestMultipleAnnotationsStub;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\UnusedAnnotationStub;

/**
 * @covers \EoneoPay\Utils\AnnotationReader
 */
class AnnotationReaderTest extends TestCase
{
    /**
     * Ensure exception isn't thrown if an invalid class is specified
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException If opcache is disabled
     */
    public function testAnnontationReadingInvalidPropertyOrClassDoesNotThrowException(): void
    {
        self::assertEmpty((new AnnotationReader())->getClassPropertyAnnotations('blah', ['test']));
    }

    /**
     * Ensure annotations can be fetched from a class which uses them
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException If opcache is disabled
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
     * Ensure method annotations are available
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException If opcache is disabled
     * @throws \ReflectionException
     */
    public function testAnnotationsCanBeReadFromMethod(): void
    {
        $annotations = (new AnnotationReader())->getClassMethodAnnotations(
            new ReflectionMethod(AnnotationReaderParentStub::class, 'method')
        );

        self::assertCount(1, $annotations);
        self::assertContainsOnlyInstancesOf(TestAnnotationStub::class, $annotations);
        self::assertSame('method', $annotations[0]->name);
    }

    /**
     * Ensure annotations can be read recursively through parents and traits
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException If opcache is disabled
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
     * Ensure multiple annotations can be read recursively through parents and traits
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException If opcache is disabled
     */
    public function testMultipleAnnotationsCanBeReadRecursivelyFromClass(): void
    {
        $annotations = (new AnnotationReader())->getClassPropertyAnnotations(
            AnnotationReaderStub::class,
            [TestAnnotationStub::class, TestMultipleAnnotationsStub::class]
        );

        $tests = [
            'baseProperty' => 2,
            'parent' => 4,
            'trait' => 2,
            'parentTrait' => 3
        ];

        foreach ($tests as $key => $count) {
            self::assertArrayHasKey($key, $annotations);
            self::assertCount($count, $annotations[$key]);
        }

        self::assertArrayNotHasKey('noAnnotation', $annotations);
    }

    /**
     * Ensure unused annotations return empty array
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException If opcache is disabled
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
