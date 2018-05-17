<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Arr;

use EoneoPay\Utils\Arr;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\Arr::intersect
 */
class IntersectTest extends TestCase
{
    /**
     * The destination array to test with
     *
     * @var string[]
     */
    private static $destination = [
        'one' => 'x',
        'two' => 'b'
    ];

    /**
     * The source array to test with
     *
     * @var string[]
     */
    private static $source = [
        'one' => 'a',
        'three' => 'c',
        'four' => 'd'
    ];

    /**
     * Test intersecting two arrays
     *
     * @return void
     */
    public function testIntersectCopysKeysFromSourceToDestination(): void
    {
        self::assertSame(
            ['one' => 'a', 'two' => 'b'],
            (new Arr())->intersect(self::$destination, self::$source, ['one'])
        );
    }

    /**
     * Test keys which don't exist in source are ignored
     *
     * @return void
     */
    public function testIntersectIgnoresKeysWhichDontExistOnSource(): void
    {
        self::assertSame(self::$destination, (new Arr())->intersect(self::$destination, self::$source, ['two']));
    }

    /**
     * Test different keys on the source and destination
     *
     * @return void
     */
    public function testIntersectMapsKeysBetweenDestinationAndSource(): void
    {
        // Destination $two will be replaced by source $one
        self::assertSame(
            ['one' => 'x', 'two' => 'a'],
            (new Arr())->intersect(self::$destination, self::$source, ['two' => 'one'])
        );
    }

    /**
     * Test keys with mixed key/value value pairs
     *
     * @return void
     */
    public function testIntersectMapsValuesIfNoKeyIsProvided(): void
    {
        self::assertSame(
            ['one' => 'x', 'two' => 'a', 'four' => 'd'],
            (new Arr())->intersect(self::$destination, self::$source, ['two' => 'one', 'four'])
        );
    }
}
