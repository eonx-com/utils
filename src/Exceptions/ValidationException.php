<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

/**
 * Exception thrown if an error related to bad input data occurs.
 */
abstract class ValidationException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return self::DEFAULT_STATUS_CODE_VALIDATION;
    }
}
