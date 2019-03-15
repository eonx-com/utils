<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

class InvalidAnchorException extends RuntimeException
{
    /**
     * @inheritdoc
     */
    public function getErrorCode(): int
    {
        return static::DEFAULT_ERROR_CODE_RUNTIME;
    }

    /**
     * @inheritdoc
     */
    public function getErrorSubCode(): int
    {
        return 1;
    }
}
