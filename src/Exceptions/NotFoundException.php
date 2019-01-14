<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Interfaces\Exceptions\ClientExceptionInterface;

/**
 * Exception thrown if an error related to requested resource not found occurs.
 */
abstract class NotFoundException extends BaseException implements ClientExceptionInterface
{
    /**
     * @inheritdoc
     */
    public function getStatusCode(): int
    {
        return self::DEFAULT_STATUS_CODE_NOT_FOUND;
    }
}
