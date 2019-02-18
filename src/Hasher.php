<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\HashingFailedException;
use EoneoPay\Utils\Interfaces\HasherInterface;

class Hasher implements HasherInterface
{
    /**
     * Algorithm constant value compatible with PHP's password hashing function
     *
     * @var int
     */
    private $algorithm;

    /**
     * @var mixed[]
     */
    private $options;

    /**
     * Hasher constructor.
     *
     * @param int|null $algorithm
     * @param mixed[]|null $options
     */
    public function __construct(?int $algorithm = null, ?array $options = null)
    {
        $this->algorithm = $algorithm ?? self::DEFAULT_ALGORITHM;
        $this->options = $options ?? [];
    }

    /**
     * @inheritdoc
     *
     * @throws \EoneoPay\Utils\Exceptions\HashingFailedException
     */
    public function hash(string $string): string
    {
        $hash = \password_hash($string, $this->algorithm, $this->options);

        // False is mainly associated with *nix/system crypt library faults or poor configuration
        if ($hash !== false) {
            return $hash;
        }

        // Ignored due to difficulty of replication without system or library corruption
        throw new HashingFailedException('Value was not able to be hashed'); // @codeCoverageIgnore
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
