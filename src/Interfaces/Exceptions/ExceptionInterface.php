<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces\Exceptions;

use Throwable;

interface ExceptionInterface extends Throwable
{
    /**
     * Get Error code.
     *
     * @return int
     */
    public function getErrorCode(): int;

    /**
     * Get Error sub-code.
     *
     * @return int
     */
    public function getErrorSubCode(): int;

    /**
     * Get Error Response status code.
     *
     * @return int
     */
    public function getStatusCode(): int;
}
