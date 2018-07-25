<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\GeneratorInterface;
use Exception;

class Generator implements GeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function randomString(?int $length = null, ?int $flags = null): string
    {
        // Set defaults
        $length = $length ?? 16;
        $flags = $flags ?? self::RANDOM_INCLUDE_ALPHA_LOWERCASE | self::RANDOM_INCLUDE_INTEGERS;

        try {
            return $this->generateTrueRandomString($length, $flags);
        } /** @noinspection BadExceptionsProcessingInspection */ catch (Exception $exception) {
            // It's unlikely exception will be thrown as system is running *nix
            return $this->generatePseudoRandomString($length, $flags);
        }
    }

    /**
     * Generate a truly random string
     *
     * This method is protected only for testing/code coverage purposes
     *
     * @param int $length The length of the string to return, defaults to 16
     * @param int $flags Flags used to generate character map
     *
     * @return string
     *
     * @throws \Exception If not enough entropy can be gathered by \random_bytes or \random_int
     */
    protected function generateTrueRandomString(int $length, int $flags): string
    {
        $codes = \array_map('\ord', $this->getCharacters($flags));
        $count = \count($codes) - 1;

        // Generate an array of random characters up to length
        $random = [];
        for ($iteration = 0; $iteration < $length; $iteration++) {
            $random[] = \chr($codes[\random_int(0, $count)]);
        }

        return \implode('', $random);
    }

    /**
     * Generate a pseudo random string
     *
     * @param int $length The length of the string to return, defaults to 16
     * @param int $flags Flags used to generate character map
     *
     * @return string
     */
    private function generatePseudoRandomString(int $length, int $flags): string
    {
        $characters = $this->getCharacters($flags);

        // Generate an array of random characters up to length
        $random = [];
        for ($iteration = 0; $iteration < $length; $iteration++) {
            $random[] = $characters[\array_rand($characters)];
        }

        return \implode('', $random);
    }

    /**
     * Get characters based on flags
     *
     * @param int $flags Flags used to generate character map
     *
     * @return string[]
     */
    private function getCharacters(int $flags): array
    {
        // Determine included characters
        $includes = [
            self::RANDOM_INCLUDE_ALPHA_LOWERCASE => \str_split('abcdefghijklmnopqrstuvwxyz'),
            self::RANDOM_INCLUDE_ALPHA_UPPERCASE => \str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),
            self::RANDOM_INCLUDE_INTEGERS => \str_split('0123456789'),
            self::RANDOM_INCLUDE_SYMBOLS => \str_split('-=[]\\;\',./~!@#$%^&*()_+{}|:"<>?')
        ];

        $characters = [];

        foreach ($includes as $key => $values) {
            if (($key & $flags) === $key) {
                $characters[] = $values;
            }
        }

        // Flatten characters array into a single dimension
        $characters = \array_merge(... $characters);

        // Remove any excluded characters
        $excludes = [
            self::RANDOM_EXCLUDE_SIMILAR => \str_split('iIlLoOqQsS015!$'),
            self::RANDOM_EXCLUDE_AMBIGIOUS => \str_split('-[]\\;\',./!()_{}:"<>?')
        ];

        foreach ($excludes as $key => $values) {
            if (($key & $flags) === $key) {
                $characters = \array_diff($characters, $values);
            }
        }

        // Return as a single dimensional array
        return $characters;
    }
}
