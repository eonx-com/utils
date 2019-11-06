<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\GeneratorInterface;
use Exception;

class Generator implements GeneratorInterface
{
    /**
     * @inheritdoc
     */
    public function randomInteger(?int $minimum = null, ?int $maximum = null): int
    {
        $minimum = $minimum ?? 0;
        $maximum = $maximum ?? \PHP_INT_MAX;

        try {
            return \random_int($minimum, $maximum);
            // @codeCoverageIgnoreStart
        } /** @noinspection BadExceptionsProcessingInspection */ catch (Exception $exception) {
            // It's unlikely exception will be thrown as system is running *nix
            /** @noinspection RandomApiMigrationInspection Fallback in case random_int throws exception */
            return \mt_rand($minimum, $maximum);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @inheritdoc
     */
    public function randomString(?int $length = null, ?int $flags = null): string
    {
        // Set defaults
        $length = $length ?? 16;
        $flags = $flags ?? self::RANDOM_INCLUDE_ALPHA_LOWERCASE | self::RANDOM_INCLUDE_INTEGERS;

        $codes = \array_values(\array_map('\ord', $this->getCharacters($flags)));
        $max = \count($codes) - 1;

        // Generate an array of random characters up to length
        $random = [];
        for ($iteration = 0; $iteration < $length; $iteration++) {
            $random[] = \chr($codes[$this->randomInteger(0, $max)]);
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
            self::RANDOM_INCLUDE_ALPHA_LOWERCASE => \str_split(self::INCLUDE_ALPHA_LOWERCASE),
            self::RANDOM_INCLUDE_ALPHA_UPPERCASE => \str_split(self::INCLUDE_ALPHA_UPPERCASE),
            self::RANDOM_INCLUDE_INTEGERS => \str_split(self::INCLUDE_INTEGERS),
            self::RANDOM_INCLUDE_SYMBOLS => \str_split(self::INCLUDE_SYMBOLS)
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
            self::RANDOM_EXCLUDE_SIMILAR => \str_split(self::EXCLUDED_SIMILAR),
            self::RANDOM_EXCLUDE_AMBIGIOUS => \str_split(self::EXCLUDED_AMBIGIOUS),
            self::RANDOM_EXCLUDE_VOWELS => \str_split(self::EXCLUDED_VOWELS)
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
