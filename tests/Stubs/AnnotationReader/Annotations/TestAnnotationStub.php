<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * @Target({"METHOD", "PROPERTY"})
 */
final class TestAnnotationStub extends Annotation
{
    /**
     * Whether the property is enabled or not
     *
     * @var string
     */
    public $enabled;
    /**
     * The property name
     *
     * @var string
     */
    public $name;
}
