<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Interfaces\Exceptions\ValidationExceptionInterface;
use Throwable;

/**
 * Exception thrown if an error related to bad input data occurs.
 */
abstract class ValidationException extends BaseException implements ValidationExceptionInterface
{
    /**
     * Validation errors.
     *
     * @var mixed[]
     */
    private $errors;

    /**
     * Create validation exception
     *
     * @param string|null $message
     * @param int|null $code
     * @param \Throwable|null $previous
     * @param mixed[]|null $errors
     */
    public function __construct(
        ?string $message = null,
        ?int $code = null,
        ?Throwable $previous = null,
        ?array $errors = null
    ) {
        parent::__construct($message ?? '', $code ?? 0, $previous);

        $this->errors = $errors ?? [];
    }

    /**
     * @inheritdoc
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @inheritdoc
     */
    public function getStatusCode(): int
    {
        return self::DEFAULT_STATUS_CODE_VALIDATION;
    }
}
