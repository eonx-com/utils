<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\BaseExceptionInterface;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren) Every test ultimately extends this class
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Process test for codes getters on BaseExceptionInterface implementations.
     *
     * @param \EoneoPay\Utils\Interfaces\BaseExceptionInterface $exception
     * @param int $errorCode
     * @param int $errorSubCode
     * @param int $statusCode
     *
     * @return void
     */
    protected function processExceptionCodesTest(
        BaseExceptionInterface $exception,
        int $errorCode,
        int $errorSubCode,
        int $statusCode
    ): void {
        self::assertEquals($errorCode, $exception->getErrorCode());
        self::assertEquals($errorSubCode, $exception->getErrorSubCode());
        self::assertEquals($statusCode, $exception->getStatusCode());
    }
}
