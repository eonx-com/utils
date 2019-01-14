<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces\Exceptions;

interface CriticalExceptionInterface extends ExceptionInterface
{
    /**
     * Display a friendly exception message as translator might not be available
     *
     * @return string
     */
    public function getErrorMessage(): string;
}
