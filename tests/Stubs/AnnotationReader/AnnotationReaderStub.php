<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\AnnotationReader;

use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\TestAnnotationStub;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Traits\AnnotationReaderTraitStub;
use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\TestMultipleAnnotationsStub;

class AnnotationReaderStub extends AnnotationReaderParentStub
{
    use AnnotationReaderTraitStub;

    /**
     * Test base annotation
     *
     * @var null
     *
     * @TestAnnotationStub(name="base_property")
     * @TestMultipleAnnotationsStub(name="multiple_base_property")
     */
    protected $baseProperty;
}
