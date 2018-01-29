<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Permissions;

class PermissionsTest extends TestCase
{
    /**
     * @var int
     */
    private const PERMISSIONS_VALUE = 13;

    /**
     * @var array
     */
    private static $permissions = [1, 1, 4, 8];

    /**
     * Permissions should return right value for given permissions array.
     */
    public function testGetPermissionsValue(): void
    {
        self::assertEquals(self::PERMISSIONS_VALUE, (new Permissions())->getPermissionsValue(static::$permissions));
    }

    /**
     * Permissions should return true/false if given value is/isn't in $permissions.
     */
    public function testHasPermissions(): void
    {
        $permissions = new Permissions();

        self::assertTrue($permissions->hasPermission(4, $permissions->getPermissionsValue(static::$permissions)));
        self::assertFalse($permissions->hasPermission(2, $permissions->getPermissionsValue(static::$permissions)));
    }
}
