<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface AnnotationReaderInterface
{
    /**
     * Resolve annotation values from a specific method
     *
     * @param string $class
     * @param string $method
     * @param string $annotation
     *
     * @return mixed
     */
    public function getClassMethodAnnotation(string $class, string $method, string $annotation);

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
}
