<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Hasher;

/**
 * @covers \EoneoPay\Utils\Hasher
 */
class HasherTest extends TestCase
{
    /**
     * More expensive cost value than default for bcrypt
     *
     * @var int
     */
    private static $cost = 12;

    /**
     * Generic string used for hashing
     *
     * @var string
     */
    private static $data = 'thisIsAStringToUse';

    /**
     * Verify should return true/false
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\HashingFailedException
     */
    public function testHashedValueVerification(): void
    {
        $hasher = new Hasher(\PASSWORD_BCRYPT, ['cost' => self::$cost]);

        // Hash value without a cost specified
        $hash = $hasher->hash(self::$data);
        self::assertNotFalse($hash);

        // Ensure hashed value is based off the same original value
        self::assertTrue($hasher->verify(self::$data, $hash));
        self::assertFalse($hasher->verify('incorrectValue', $hash));

        // Hash value with specified cost
        $hashWithCost = $hasher->hash(self::$data);
        self::assertNotFalse($hashWithCost);

        // Ensure hashed value with higher cost is based off the same original value
        self::assertTrue($hasher->verify(self::$data, $hashWithCost));
        self::assertFalse($hasher->verify('incorrectValue', $hashWithCost));
    }

    /**
     * Hasher should not fail and ensure options are used.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\HashingFailedException
     */
    public function testHashingFunction(): void
    {
        $hasher = new Hasher(\PASSWORD_BCRYPT, ['cost' => self::$cost]);

        // Test without setting cost
        $string = $hasher->hash(self::$data);
        self::assertNotFalse($string);

        // Test with cost higher than algorithm default (10)
        $string = $hasher->hash(self::$data);
        self::assertNotFalse($string);

        $hashInfo = \password_get_info($string);
        self::assertArrayHasKey('options', $hashInfo);
        self::assertArrayHasKey('cost', $hashInfo['options']);
        self::assertEquals($hashInfo['options']['cost'], self::$cost);
    }
}
