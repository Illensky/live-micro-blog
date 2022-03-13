<?php

namespace App\Model\Manager;

use App\Model\DBSingleton;
use App\Model\Entity\Role;
use App\Model\Entity\User;

final class RoleManager
{
    public const TABLE = 'role';
    public const ROLE_USER = 'user';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_ADMIN = 'admin';

    public static function getAll(): array
    {
        $roles = [];
        $query = DBSingleton::PDO()->query("
            SELECT * FROM ". self::TABLE
        );
        if ($query) {
            foreach ($query->fetchAll() as $roleData) {
                $roles[] = (new Role())
                ->setId($roleData['id'])
                ->setRoleName($roleData['role_name'])
                ;
            }
        }
        return $roles;
    }

    /**
     * Return all given user roles.
     * @param int $roleId
     * @return array
     */
    public static function getRolesByUser(User $user): array
    {
        $roles = [];
        $rolesQuery = DBSingleton::PDO()->query("
            SELECT * FROM role WHERE id IN (SELECT role_fk FROM user_role WHERE user_fk = {$user->getId()});
        ");

        if($rolesQuery){
            foreach($rolesQuery->fetchAll() as $roleData) {
                $roles[] = (new Role())
                    ->setId($roleData['id'])
                    ->setRoleName($roleData['role_name'])
                ;
            }
        }

        return $roles;
    }


    /**
     * Return a role by name.
     * @param string $roleName
     * @return Role
     */
    public static function getRoleByName(string $roleName): Role
    {
        $role = new Role();
        $rQuery = DBSingleton::PDO()->query("
            SELECT * FROM role WHERE role_name = '".$roleName."'
        ");
        if($rQuery && $roleData = $rQuery->fetch()) {
            $role->setId($roleData['id']);
            $role->setRoleName($roleData['role_name']);
        }
        return $role;
    }
}