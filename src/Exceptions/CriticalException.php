<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

/**
 * Exception thrown if an error which is critical and requires human intervention to resolve occurs.
 */
abstract class CriticalException extends BaseException
{
    /**
     * Display a friendly exception message
     *
     * @return string
     */
    abstract public function getErrorMessage(): string;

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return self::DEFAULT_STATUS_CODE_CRITICAL;
    }
}
