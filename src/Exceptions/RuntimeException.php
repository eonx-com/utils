<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

/**
 * Exception thrown if an error which can only be found on runtime occurs.
 */
abstract class RuntimeException extends BaseException
{
    /**
     * @inheritdoc
     */
    public function getStatusCode(): int
    {
        return self::DEFAULT_STATUS_CODE_RUNTIME;
    }
}
