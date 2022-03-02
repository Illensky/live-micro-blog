<?php

namespace App\Model\Manager;

use App\Model\Entity\User;
use App\Model\DBSingleton;

class UserManager
{
    public function getUserById (int $id): User
    {
        $user = new User;

        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM user
            WHERE id = $id
        ");

        if ($query) {
            $userData = $query->fetchAll();
            $user
                ->setId($userData['id'])
                ->setPassword($userData['password'])
                ->setLastname($userData['name'])
                ->setFirstname($userData['surname'])
                ->setAge($userData['age'])
                ->setEmail($userData['email'])
            ;
        }

        return $user;
    }
}