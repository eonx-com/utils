<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs;

use EoneoPay\Utils\Generator;
use Exception;

class GeneratorStub extends Generator
{
    /** @noinspection PhpMissingParentCallCommonInspection Parent method is private */
    /**
     * Generate a truly random string
     *
     * @param int|null $length The length of the string to return, defaults to 16
     *
     * @return string
     *
     * @throws \Exception If not enough entropy can be gathered by \random_bytes or \random_int
     */
    protected function generateTrueRandomString(?int $length): string
    {
        /** @noinspection ThrowRawExceptionInspection This is what \random_bytes and \random_int return */
        throw new Exception('Entropy generation failure');
    }
}
