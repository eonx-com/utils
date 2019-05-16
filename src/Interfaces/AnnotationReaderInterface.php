<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

use ReflectionMethod;

interface AnnotationReaderInterface
{
    /**
     * Get values for a specific annotation within a class recursively as [<property> => <annotation>]
     *
     * @param string $class
     * @param string $annotation
     *
     * @return mixed[]
     */
    public function getClassPropertyAnnotation(string $class, string $annotation): array;

    /**
     * Get values for annotations within a class recursively as [<property> => [<annotation>, <annotation>, ...]]
     *
     * @param string $class
     * @param mixed[] $annotations
     *
     * @return mixed[]
     */
    public function getClassPropertyAnnotations(string $class, array $annotations): array;

    /**
     * Gets the annotations applied to a method.
     *
     * @param \ReflectionMethod $method
     *
     * @return mixed[] An array of Annotations.
     */
    public function getClassMethodAnnotations(ReflectionMethod $method): array;
}
