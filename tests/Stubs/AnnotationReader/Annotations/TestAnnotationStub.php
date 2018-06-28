<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * @Target("PROPERTY")
 */
final class TestAnnotationStub extends Annotation
{
    /**
     * The property name
     *
     * @var string
     */
    public $name;

    /**
     * Whether the property is enabled or not
     *
     * @var string
     */
    public $enabled;
}
