<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface BaseExceptionInterface
{
    /**
     * Default error code for critical.
     *
     * @const int
     */
    public const DEFAULT_ERROR_CODE_CRITICAL = 9000;
    /**
     * Default error code for not found.
     *
     * @const int
     */
    public const DEFAULT_ERROR_CODE_NOT_FOUND = 1400;
    /**
     * Default error code for runtime.
     *
     * @const int
     */
    public const DEFAULT_ERROR_CODE_RUNTIME = 1100;
    /**
     * Default error code for validation.
     *
     * @const int
     */
    public const DEFAULT_ERROR_CODE_VALIDATION = 1000;
    /**
     * Default error sub-code.
     *
     * @const int
     */
    public const DEFAULT_ERROR_SUB_CODE = 0;

    /**
     * Default status code for critical.
     *
     * @const int
     */
    public const DEFAULT_STATUS_CODE_CRITICAL = 500;

    /**
     * Default status code for not found.
     *
     * @const int
     */
    public const DEFAULT_STATUS_CODE_NOT_FOUND = 404;
    /**
     * Default status code for runtime.
     *
     * @const int
     */
    public const DEFAULT_STATUS_CODE_RUNTIME = 500;
    /**
     * Default status code for validation.
     *
     * @const int
     */
    public const DEFAULT_STATUS_CODE_VALIDATION = 400;

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
