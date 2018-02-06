<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\GeneratorInterface;

class Generator implements GeneratorInterface
{
    /** @noinspection PhpDocMissingThrowsInspection Exceptions will only be thrown if can't find entropy generator */
    /**
     * Generate a random string
     *
     * @param int|null $length The length of the string to return, defaults to 16
     *
     * @return string
     */
    public function randomString(?int $length = null): string
    {
        // Generate a string of random bytes
        /** @noinspection PhpUnhandledExceptionInspection Only thrown if can't find entropy generator */
        $string = \bin2hex(\random_bytes(1024));

        // Remove any ambiguous characters
        $string = \preg_replace('/[ilos015]/i', '', $string);

        // The length of the string to return
        $length = $length ?? 16;

        // Get a random start position
        /** @noinspection PhpUnhandledExceptionInspection Only thrown if can't find entropy generator */
        $start = \random_int(0, \strlen($string) - $length - 1);

        return \substr($string, $start, $length);
    }
}
