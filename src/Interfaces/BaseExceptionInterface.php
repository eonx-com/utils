<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface BaseExceptionInterface
{
    /**
     * Default error code for validation.
     *
     * @var int
     */
    public const DEFAULT_ERROR_CODE_VALIDATION = 1000;

    /**
     * Default error code for runtime.
     *
     * @var int
     */
    public const DEFAULT_ERROR_CODE_RUNTIME = 1100;

    /**
     * Default error code for not found.
     *
     * @var int
     */
    public const DEFAULT_ERROR_CODE_NOT_FOUND = 1200;

    /**
     * Default error sub-code.
     *
     * @var int
     */
    public const DEFAULT_ERROR_SUB_CODE = 0;

    /**
     * Default status code for not found.
     *
     * @var int
     */
    public const DEFAULT_STATUS_CODE_NOT_FOUND = 404;

    /**
     * Default status code for validation.
     *
     * @var int
     */
    public const DEFAULT_STATUS_CODE_VALIDATION = 400;

    /**
     * Default status code for runtime.
     *
     * @var int
     */
    public const DEFAULT_STATUS_CODE_RUNTIME = 500;

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
