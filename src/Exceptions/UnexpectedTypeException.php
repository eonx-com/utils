<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use Throwable;

class UnexpectedTypeException extends RuntimeException
{
    /**
     * @param mixed $object
     * @param string $expectedType
     * @param int|null $code
     * @param null|\Throwable $previous
     */
    public function __construct($object, string $expectedType, ?int $code = null, ?Throwable $previous = null)
    {
        $message = \sprintf(
            'Unexpected type "%s" found, expected "%s"',
            \get_class($object),
            $expectedType
        );

        parent::__construct($message, $code, $previous);
    }

    /**
     * Error code
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::DEFAULT_ERROR_CODE_RUNTIME;
    }

    /**
     * Error sub code
     *
     * @return int
     */
    public function getErrorSubCode(): int
    {
        return self::DEFAULT_ERROR_SUB_CODE;
    }
}
