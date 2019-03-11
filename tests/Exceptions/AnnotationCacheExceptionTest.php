<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\AnnotationCacheException;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Exceptions\AnnotationCacheException
 *
 * @uses \EoneoPay\Utils\Exceptions\BaseException
 * @uses \EoneoPay\Utils\Exceptions\CriticalException
 */
class AnnotationCacheExceptionTest extends TestCase
{
    /**
     * Exception should return valid codes.
     *
     * @return void
     */
    public function testGettersFromBaseExceptionInterface(): void
    {
        $this->processExceptionCodesTest(new AnnotationCacheException(), 9000, 0, 500);

        self::assertSame(
            'Opcode caching is not available, unable to use annotations',
            (new AnnotationCacheException())->getErrorMessage()
        );
    }
}
