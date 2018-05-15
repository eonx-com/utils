<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\PermissionsInterface;

class Permissions implements PermissionsInterface
{
    /**
     * Calculate total value for given permissions.
     *
     * @param mixed[] $permissions
     *
     * @return int
     */
    public function getPermissionsValue(array $permissions): int
    {
        $value = 0;

        foreach ($permissions as $permission) {
            $value |= $permission;
        }

        return $value;
    }

    /**
     * Check if given permission is present in given permissions.
     *
     * @param int $permission Current permission value
     * @param int $permissions Total value of permissions
     *
     * @return bool
     */
    public function hasPermission(int $permission, int $permissions): bool
    {
        return ($permissions & $permission) !== 0;
    }
}
