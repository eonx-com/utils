<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Interfaces\BaseExceptionInterface;
use Exception;

abstract class BaseException extends Exception implements BaseExceptionInterface
{
    /**
     * Get Error code.
     *
     * @return int
     */
    abstract public function getErrorCode(): int;

    /**
     * Get Error sub-code.
     *
     * @return int
     */
    abstract public function getErrorSubCode(): int;

    /**
     * Get Error Response status code.
     *
     * @return int
     */
    abstract public function getStatusCode(): int;
}
