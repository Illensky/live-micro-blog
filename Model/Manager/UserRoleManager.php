<?php

namespace App\Model\Manager;

use App\Model\DBSingleton;
use App\Model\Entity\User;
use App\Model\Entity\Role;

class UserRoleManager
{
    public function getUsersByRoleId(int $roleId): array
    {
        $users = [];

        $usersQuery = DBSingleton::PDO()->query("
                    SELECT * 
                    FROM user 
                    WHERE id IN (SELECT user_fk FROM user_role WHERE role_fk = $roleId)
                    "
                    /*
                    "
                    SELECT user.*
                    FROM user_role
                    LEFT JOIN user ON user_role.role_fk = $roleId
                    "
                    */
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

    public function getRoleByUserId(): array
    {
        $roles = [];
        return $roles;
    }
}