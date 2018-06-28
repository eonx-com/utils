<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * @Target("PROPERTY")
 */
class TestMultipleAnnotationsStub extends Annotation
{
    /**
     * The property name
     *
     * @var string
     */
    public $name;
}
