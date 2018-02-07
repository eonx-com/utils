<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\GeneratorInterface;

class Generator implements GeneratorInterface
{
    /**
     * Generate a random string
     *
     * @param int|null $length The length of the string to return, defaults to 16
     *
     * @return string
     */
    public function randomString(?int $length = null): string
    {
        // Character set without ambiguous characters i, l, o, s, 0, 1, 5
        $characters = \str_split('2346789abcdefghjkmnpqrtuvwxyzABCDEFGHJKMNPQRTUVWXYZ');

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
