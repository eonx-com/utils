<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface PermissionsInterface
{
    /**
     * Calculate total value for given permissions.
     *
     * @param int[] $permissions
     *
     * @return int
     */
    public function getPermissionsValue(array $permissions): int;

    /**
     * Check if given permission is present in given permissions.
     *
     * @param int $permission Current permission value
     * @param int $permissions Total value of permissions
     *
     * @return bool
     */
    public function hasPermission(int $permission, int $permissions): bool;
}
