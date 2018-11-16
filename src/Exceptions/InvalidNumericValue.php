<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

class InvalidNumericValue extends ValidationException
{
    /**
     * @inheritdoc
     */
    public function getErrorCode(): int
    {
        return self::DEFAULT_ERROR_CODE_VALIDATION + 10;
    }

    /**
     * @inheritdoc
     */
    public function getErrorSubCode(): int
    {
        return self::DEFAULT_ERROR_SUB_CODE + 1;
    }
}
