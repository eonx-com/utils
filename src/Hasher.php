<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\HashingFailedException;
use EoneoPay\Utils\Interfaces\HasherInterface;

class Hasher implements HasherInterface
{
    /**
     * Hash a string using configured options
     *
     * @param string $string
     * @param null|int $cost
     *
     * @return string
     *
     * @throws \EoneoPay\Utils\Exceptions\HashingFailedException
     */
    public function hash(string $string, ?int $cost = null): string
    {
        $hash = \password_hash($string, self::ALGORITHM, ['cost' => $cost ?? self::DEFAULT_COST]);

        // False is mainly associated with *nix/system crypt library faults or poor configuration
        if ($hash !== false) {
            return $hash;
        }

        // @codeCoverageIgnoreStart
        // Ignored due to difficulty of replication without system or library corruption
        throw new HashingFailedException('Value was not able to be hashed');
        // @codeCoverageIgnoreEnd
    }

    /**
     * Validate that a provided string matches the hashed version
     *
     * @param string $string
     * @param string $hash Algorithm and associated options are part of the hash value
     *
     * @return bool
     */
    public function verify(string $string, string $hash): bool
    {
        return \password_verify($string, $hash);
    }
}
