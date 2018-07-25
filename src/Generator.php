<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\GeneratorInterface;
use Exception;

class Generator implements GeneratorInterface
{
    /**
     * Generate a non-ambiguous string.
     *
     * @param int|null $length
     *
     * @return string
     *
     * @throws \Exception
     */
    public function generateNonAmbiguousString(?int $length = null): string
    {
        $asciiCodes = \array_map('\ord', \str_split('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'));
        $totalCodes = \count($asciiCodes) - 1;

        // The length of the string to return
        $length = $length ?? 16;

        $string = '';

        for ($iteration = 0; $iteration < $length; $iteration++) {
            $string .= \chr($asciiCodes[\random_int(0, $totalCodes)]);
        }

        return $string;
    }

    /**
     * Generate a random string
     *
     * @param int|null $length The length of the string to return, defaults to 16
     *
     * @return string
     */
    public function randomString(?int $length = null): string
    {
        try {
            return $this->generateTrueRandomString($length);
        } /** @noinspection BadExceptionsProcessingInspection */ catch (Exception $exception) {
            // It's unlikely exception will be thrown as system is running *nix
            return $this->generatePseudoRandomString($length);
        }
    }

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
        // Generate a string of random bytes
        $string = \bin2hex(\random_bytes(512));

        // The length of the string to return
        $length = $length ?? 16;

        // Get a random start position
        $start = \random_int(0, \strlen($string) - $length - 1);

        return \substr($string, $start, $length);
    }

    /** @noinspection ExceptionsAnnotatingAndHandlingInspection Exception will be thrown on non-*nix systems */

    /**
     * Generate a pseudo random string
     *
     * This method is protected only for testing/code coverage purposes
     *
     * @param int|null $length The length of the string to return, defaults to 16
     *
     * @return string
     */
    private function generatePseudoRandomString(?int $length): string
    {
        $characters = \str_split('0123456789abcdef');

        // Ensure a length exists
        $length = $length ?? 16;

        // Generate an array of random characters up to length
        $random = [];
        for ($loop = 0; $loop < $length; $loop++) {
            $random[] = $characters[\array_rand($characters)];
        }

        return \implode('', $random);
    }
}
