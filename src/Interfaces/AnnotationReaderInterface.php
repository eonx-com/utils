<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface AnnotationReaderInterface
{
    /**
     * Resolve annotations from a specific method
     *
     * @param string $class Class annotations should be read against
     * @param string $method Method within the class annotations will be resolved
     * @param string $annotation FQCN of the annotation class
     *
     * @return mixed[] Returns all instantiated annotation classes based on the $annotation argument
     */
    public function getClassMethodAnnotations(string $class, string $method, string $annotation): array;

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
