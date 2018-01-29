<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\AnnotationReader\Traits;

use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\TestAnnotationStub;

trait AnnotationReaderTraitStub
{
    use AnnotationReaderParentTraitStub;

    /**
     * Test trait annotation
     *
     * @var null
     *
     * @TestAnnotationStub(name="trait_property", enabled=false)
     */
    protected $trait;
}
