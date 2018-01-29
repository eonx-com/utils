<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\AnnotationReader;

use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\TestAnnotationStub;

class AnnotationReaderParentStub
{
    /**
     * Test parent annotation
     *
     * @var null
     *
     * @TestAnnotationStub(name="parent_property", enabled=true)
     */
    protected $parent;
}
