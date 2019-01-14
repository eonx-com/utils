<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Interfaces\Exceptions\CriticalExceptionInterface;

/**
 * Exception thrown if an error which is critical and requires human intervention to resolve occurs.
 */
abstract class CriticalException extends BaseException implements CriticalExceptionInterface
{
    /**
     * @inheritdoc
     */
    public function getStatusCode(): int
    {
        return self::DEFAULT_STATUS_CODE_CRITICAL;
    }
}
