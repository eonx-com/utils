<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\AnnotationReader\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * @Target("METHOD")
 *
 * @coversNothing
 */
class MethodAnnotationStub extends Annotation
{
}
