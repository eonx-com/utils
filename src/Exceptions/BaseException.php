<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Interfaces\BaseExceptionInterface;
use Exception;
use Throwable;

abstract class BaseException extends Exception implements BaseExceptionInterface
{
    /**
     * @var mixed[]
     */
    protected $messageParams;

    /**
     * BaseException constructor.
     *
     * @param null|string $message
     * @param int|null $code
     * @param \Throwable|null $previous
     * @param mixed[]|null $messageParameters Parameters for $message
     */
    public function __construct(
        ?string $message = null,
        ?int $code = null,
        ?Throwable $previous = null,
        ?array $messageParameters = null
    ) {
        parent::__construct($message ?? '', $code ?? 0, $previous);
        $this->messageParams = $messageParameters ?? [];
    }

    /**
     * @inheritdoc
     */
    public function getMessageParameters(): array
    {
        return $this->messageParams;
    }
}
