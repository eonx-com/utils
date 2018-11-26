<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

class AnnotationCacheException extends CriticalException
{
    /**
     * @inheritdoc
     */
    public function getErrorCode(): int
    {
        return self::DEFAULT_ERROR_CODE_CRITICAL;
    }

    /**
     * @inheritdoc
     */
    public function getErrorMessage(): string
    {
        return 'Opcode caching is not available, unable to use annotations';
    }

    /**
     * @inheritdoc
     */
    public function getErrorSubCode(): int
    {
        return self::DEFAULT_ERROR_SUB_CODE;
    }
}
