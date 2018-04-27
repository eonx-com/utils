<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Hasher;

//use Tests\EoneoPay\Utils\Stubs\HaherStub;

/**
 * @covers \EoneoPay\Utils\Hasher
 */
class HasherTest extends TestCase
{

    /**
     * Generic string used for hashing
     *
     * @var string
     */
    private static $data = 'thisIsAStringToUse';

    /**
     * More expensive cost value than default for bcrypt
     *
     * @var int
     */
    private static $cost = 12;

    /**
     * Hasher should not fail and ensure options are used.
     *
     * @throws \EoneoPay\Utils\Exceptions\HashingFailedException
     */
    public function testHashingFunction(): void
    {
        $hasher = new Hasher();

        // Test without setting cost
        $string = $hasher->hash(self::$data);
        self::assertNotFalse($string);

        // Test with cost higher than algorithm default (10)
        $string = $hasher->hash(self::$data, 12);
        self::assertNotFalse($string);

        $hashInfo = password_get_info($string);
        self::assertArrayHasKey('options', $hashInfo);
        self::assertArrayHasKey('cost', $hashInfo['options']);
        self::assertEquals($hashInfo['options']['cost'], self::$cost);
    }

    /**
     * Verify should return true/false
     *
     * @throws \EoneoPay\Utils\Exceptions\HashingFailedException
     */
    public function testHashedValueVerification(): void
    {
        $hasher = new Hasher();

        // Hash value without a cost specified
        $hashedValueWithoutCost = $hasher->hash(self::$data);
        self::assertNotFalse($hashedValueWithoutCost);

        // Ensure hashed value is based off the same original value
        self::assertTrue($hasher->verify(self::$data, $hashedValueWithoutCost));
        self::assertFalse($hasher->verify('incorrectValue', $hashedValueWithoutCost));

        // Hash value with specified cost
        $hashedValueWithCost = $hasher->hash(self::$data, self::$cost);
        self::assertNotFalse($hashedValueWithCost);

        // Ensure hashed value with higher cost is based off the same original value
        self::assertTrue($hasher->verify(self::$data, $hashedValueWithCost));
        self::assertFalse($hasher->verify('incorrectValue', $hashedValueWithCost));

    }
}
