<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\PermissionsInterface;

class Permissions implements PermissionsInterface
{
    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function hasPermission(int $permission, int $permissions): bool
    {
        return ($permissions & $permission) === $permission;
    }
}
