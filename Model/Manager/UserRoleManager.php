<?php

namespace App\Model\Manager;

use App\Model\DBSingleton;
use App\Model\Entity\User;
use App\Model\Entity\Role;

final class UserRoleManager
{
    public const TABLE = 'user_role';

    public static function getUsersByRole(Role $role): array
    {
        $users = [];

        $usersQuery = DBSingleton::PDO()->query("
                    SELECT * 
                    FROM user 
                    WHERE id IN (SELECT user_fk FROM " . self::TABLE . " WHERE role_fk = {$role->getId()})
                    "
        );

        if ($usersQuery) {
            foreach ($usersQuery->fetchAll() as $userData) {
                $user = new User();
                $user->setId($userData['id'])
                    ->setEmail($userData['email'])
                    ->setAge($userData['age'])
                    ->setFirstname($userData['surname'])
                    ->setLastname($userData['name'])
                    ->setPassword($userData['password']);
                $users[] = $user;
            }
        }
        return $users;
    }

    public static function getRolesByUser(User $user): array
    {
        $roles = [];
        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM role
            WHERE id IN (SELECT role_fk FROM " . self::TABLE . " WHERE user_fk = {$user->getId()})
        ");

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
}