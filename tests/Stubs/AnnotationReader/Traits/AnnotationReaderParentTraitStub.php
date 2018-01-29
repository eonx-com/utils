<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\AnnotationReader\Traits;

use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\TestAnnotationStub;

trait AnnotationReaderParentTraitStub
{
    /**
     * Test trait parent annotation
     *
     * @var null
     *
     * @TestAnnotationStub(name="parent_trait_property")
     */
    protected $parentTrait;

    /**
     * Test trait parent property without annotation
     *
     * @var null
     */
    protected $noAnnotation;
}
