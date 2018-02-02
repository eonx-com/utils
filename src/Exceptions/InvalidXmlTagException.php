<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Interfaces\BaseExceptionInterface;

class InvalidXmlTagException extends RuntimeException
{
    /**
     * Get Error code.
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return BaseExceptionInterface::DEFAULT_ERROR_CODE_RUNTIME;
    }

    /**
     * Get Error sub-code.
     *
     * @return int
     */
    public function getErrorSubCode(): int
    {
        return BaseExceptionInterface::DEFAULT_ERROR_SUB_CODE;
    }
}
