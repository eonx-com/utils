<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\Exceptions;

use EoneoPay\Utils\Exceptions\ValidationException;

class ValidationExceptionStub extends ValidationException
{
    /**
     * Get Error code.
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return 1000;
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
