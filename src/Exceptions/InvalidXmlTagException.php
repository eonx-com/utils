<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

class InvalidXmlTagException extends RuntimeException
{
    /**
     * Get Error code.
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return 1100;
    }

    /**
     * Get Error sub-code.
     *
     * @return int
     */
    public function getErrorSubCode(): int
    {
        return 0;
    }
}
