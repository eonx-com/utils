<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs;

use EoneoPay\Utils\Generator;
use Exception;

class GeneratorStub extends Generator
{
    /**
     * @noinspection PhpMissingParentCallCommonInspection Parent method is private
     *
     * {@inheritdoc}
     */
    protected function generateTrueRandomString(int $length, int $flags): string
    {
        /** @noinspection ThrowRawExceptionInspection This is what \random_bytes and \random_int return */
        throw new Exception('Entropy generation failure');
    }
}
