<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader as BaseAnnotationReader;
use EoneoPay\Utils\Exceptions\AnnotationCacheException;
use ReflectionClass;
use ReflectionProperty;

/**
 * The annotation helper assists with reading annotations from a class or property
 */
class AnnotationReader extends BaseAnnotationReader
{
    /**
     * Create a new annotation reader instance
     *
     * @throws \EoneoPay\Utils\Exceptions\AnnotationCacheException If opcache isn't caching annotations
     *
     * @codeCoverageIgnore Can't test exception since opcache config can only be set in php.ini
     */
    public function __construct()
    {
        try {
            parent::__construct();
        } catch (AnnotationException $exception) {
            // Convert to AnnotationCacheException
            throw new AnnotationCacheException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * Get values for a specific annotation within a class recursively as [<property> => <annotation>]
     *
     * @param string $class
     * @param string $annotation
     *
     * @return mixed[]
     *
     * @throws \ReflectionException Inherited, if class or property does not exist
     */
    public function getClassPropertyAnnotation(string $class, string $annotation): array
    {
        // Read annotations for each property in the class
        $annotations = [];
        foreach ($this->getClassPropertiesRecursive($class) as $property) {
            $reflector = new ReflectionProperty($property->class, $property->name);
            $value = $this->getPropertyAnnotation($reflector, $annotation);

            // Only keep annotation if it has a value
            if ($value) {
                $annotations[$property->name] = $value;
            }
        }

        return $annotations;
    }

    /**
     * Get values for annotations within a class recursively as [<property> => [<annotation>, <annotation>, ...]]
     *
     * @param string $class
     * @param mixed[] $annotations
     *
     * @return mixed[]
     *
     * @throws \ReflectionException Inherited, if class or property does not exist
     */
    public function getClassPropertyAnnotations(string $class, array $annotations): array
    {
        $results = [];

        foreach ($this->getClassPropertiesRecursive($class) as $property) {
            $reflector = new ReflectionProperty($property->class, $property->name);

            foreach ($annotations as $annotation) {
                $value = $this->getPropertyAnnotation($reflector, $annotation);

                // Only keep annotation if it has a value
                if ($value) {
                    if (isset($results[$property->name]) === false) {
                        $results[$property->name] = [];
                    }

                    $results[$property->name][] = $value;
                }
            }
        }

        return $results;
    }

    /**
     * Get properties for a specific class recursively
     *
     * @param string $baseClass The class to get properties for
     *
     * @return mixed[]
     *
     * @throws \ReflectionException Inherited, if class or property does not exist
     */
    private function getClassPropertiesRecursive(string $baseClass): array
    {
        $properties = [];

        $classes = \array_merge(
            $this->getClassTraitsRecursive($baseClass),
            \class_parents($baseClass),
            [$baseClass => $baseClass]
        );

        foreach ($classes as $class) {
            $properties[] = (new ReflectionClass($class))->getProperties();
        }

        return \array_merge(...$properties);
    }

    /**
     * Get all traits used by a class and any parent classes
     *
     * @param string $baseClass The class to recursively get traits for
     *
     * @return mixed[]
     */
    private function getClassTraitsRecursive(string $baseClass): array
    {
        $results = [];

        $classes = \array_reverse(\class_parents($baseClass));
        $classes[$baseClass] = $baseClass;

        foreach ($classes as $class) {
            $results[] = $this->getTraitTraitsRecursive($class);
        }

        return \array_unique(\array_merge(...$results));
    }

    /**
     * Get all traits used by a trait, recursively
     *
     * @param string $baseTrait The trait to get traits for
     *
     * @return mixed[]
     */
    private function getTraitTraitsRecursive(string $baseTrait): array
    {
        $traits[] = \class_uses($baseTrait) ?? [];

        foreach (\reset($traits) as $trait) {
            $traits[] = $this->getTraitTraitsRecursive($trait);
        }

        return \array_merge(...$traits);
    }
}
