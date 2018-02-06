<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

class Generator
{
    /**
     * Generate a random string
     *
     * @param int|null $length The length of the string to return, defaults to 16
     *
     * @return string
     *
     * @throws \Exception If sufficient entropy can't be gathered by `random_bytes` or `random_int`
     */
    public function randomString(?int $length = null): string
    {
        // Generate a string of random bytes
        $string = \bin2hex(\random_bytes(1024));

        // Remove any ambiguous characters
        $string = \preg_replace('/[ilos015]/i', '', $string);

        // The length of the string to return
        $length = $length ?? 16;

        // Get a random start position
        $start = \random_int(0, \strlen($string) - $length - 1);

        return \substr($string, $start, $length);
    }
}
