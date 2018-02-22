<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\AnnotationReader;

use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\TestAnnotationStub;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\TestMultipleAnnotationsStub;

class AnnotationReaderParentStub
{
    /**
     * Test parent annotation
     *
     * @var null
     *
     * @TestAnnotationStub(name="parent_property", enabled=true)
     * @TestMultipleAnnotationsStub(name="multiple_parent_property")
     */
    protected $parent;
}
