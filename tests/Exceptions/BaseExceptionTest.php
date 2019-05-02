<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Exceptions;

use EoneoPay\Utils\Exceptions\BaseException;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Exceptions\BaseException
 */
class BaseExceptionTest extends TestCase
{
    /**
     * Tests getMessageParameters
     *
     * @return void
     */
    public function testGetMessageParameters(): void
    {
        $exception = new class(null, ['message' => 'hello']) extends BaseException {
            /**
             * {@inheritdoc}
             */
            public function getErrorCode(): int
            {
                return 0;
            }

            /**
             * {@inheritdoc}
             */
            public function getErrorSubCode(): int
            {
                return 0;
            }

            /**
             * {@inheritdoc}
             */
            public function getStatusCode(): int
            {
                return 0;
            }
        };

        static::assertSame(['message' => 'hello'], $exception->getMessageParameters());
    }
}
