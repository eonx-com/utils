<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\AnnotationReader;

use Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations\MethodAnnotationStub;

/**
 * @coversNothing
 */
class AnnotationReaderWithMethodStub
{
    /**
     * A test method
     *
     * @MethodAnnotationStub("yes")
     * @MethodAnnotationStub("maybe")
     *
     * @return void
     */
    public function aPublicMethod(): void
    {
    }
}
