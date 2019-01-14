<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces\Exceptions;

interface ValidationExceptionInterface extends ExceptionInterface
{
    /**
     * Get validation errors
     *
     * @return mixed[]
     */
    public function getErrors(): array;
}
