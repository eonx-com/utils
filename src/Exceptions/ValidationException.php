<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Interfaces\BaseExceptionInterface;

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
        return BaseExceptionInterface::DEFAULT_STATUS_CODE_VALIDATION;
    }
}
