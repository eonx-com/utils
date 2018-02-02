<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Interfaces\BaseExceptionInterface;

/**
 * Exception thrown if an error which can only be found on runtime occurs.
 */
abstract class RuntimeException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return BaseExceptionInterface::DEFAULT_STATUS_CODE_RUNTIME;
    }
}
