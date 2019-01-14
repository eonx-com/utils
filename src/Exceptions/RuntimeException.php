<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Interfaces\Exceptions\RuntimeExceptionInterface;

/**
 * Exception thrown if an error which can only be found on runtime occurs.
 */
abstract class RuntimeException extends BaseException implements RuntimeExceptionInterface
{
    /**
     * @inheritdoc
     */
    public function getStatusCode(): int
    {
        return self::DEFAULT_STATUS_CODE_RUNTIME;
    }
}
