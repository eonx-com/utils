<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

/**
 * Exception thrown if an error related to requested resource not found occurs.
 */
abstract class NotFoundException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return self::DEFAULT_STATUS_CODE_NOT_FOUND;
    }
}
