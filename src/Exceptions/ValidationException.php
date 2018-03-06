<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use Throwable;

/**
 * Exception thrown if an error related to bad input data occurs.
 */
abstract class ValidationException extends BaseException
{
    /**
     * Validation errors.
     *
     * @var array
     */
    private $errors;

    /**
     * EntityValidationException constructor.
     *
     * @param string|null $message
     * @param int|null $code
     * @param \Throwable|null $previous
     * @param array $errors
     */
    public function __construct(string $message = null, int $code = null, Throwable $previous = null, array $errors)
    {
        parent::__construct($message ?? '', $code ?? 0, $previous);

        $this->errors = $errors;
    }

    /**
     * Get validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return self::DEFAULT_STATUS_CODE_VALIDATION;
    }
}
