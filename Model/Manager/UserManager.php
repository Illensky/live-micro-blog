<?php

namespace App\Model\Manager;

use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\DBSingleton;

final class UserManager
{
    public const TABLE = 'user';

    /**
     * @param array $data
     * @return User
     */
    private static function hydrateUser (array $data) : User
    {
        $user = (new User())
            ->setId($data['id'])
            ->setPassword($data['password'])
            ->setLastname($data['name'])
            ->setFirstname($data['surname'])
            ->setAge($data['age'])
            ->setEmail($data['email'])
            ;
            return $user->setRoles(UserRoleManager::getRolesByUser($user));

    }

    public static function getAll () : array
    {
        $users = [];

        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM " . self::TABLE
        );

        if ($query) {
            foreach ($query->fetchAll() as $userData) {
                $users[] = self::hydrateUser($userData);
            }
        }
        return $users;
    }

    public static function getUserById (int $id): ?User
    {
        $user = new User;

        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM " . self::TABLE . "
            WHERE id = $id
        ");

        return $query ? self::hydrateUser($query->fetch()) : null;
    }

    /**
     * Check if a user exists.
     * @param int $id
     * @return bool
     */
    public static function userExists(int $id): bool
    {
        $result = DBSingleton::PDO()->query("SELECT count(*) as cnt FROM " . self::TABLE . " WHERE id = $id");
        return $result ? $result->fetch()['cnt'] : 0;
    }

    /**
     * Return current users count.
     * @return int
     */
    public static function getUsersCount(): int
    {
        $result = DBSingleton::PDO()->query("SELECT count(*) as cnt FROM " . self::TABLE);
        return $result ? $result->fetch()['cnt'] : 0;
    }

    /**
     * Return current users count.
     * @return int
     */
    public static function getMinAge(): int
    {
        $result = DBSingleton::PDO()->query("SELECT min(age) as minimum FROM " . self::TABLE);
        return $result ? $result->fetch()['minimum'] : 0;
    }

    /**
     * Delete a user from user db.
     * @param User $user
     * @return bool
     */
    public static function deleteUser(User $user): bool {
        if(self::userExists($user->getId())) {
            return (bool)DBSingleton::PDO()->query("
            DELETE FROM " . self::TABLE . " WHERE id = {$user->getId()}
        ");
        }
        return false;
    }

    /**
     * Check if a user exists with its email.
     * @param string $mail
     * @return bool
     */
    public static function userMailExists(string $mail): bool
    {
        $result = DBSingleton::PDO()->query("SELECT count(*) as cnt FROM " . self::TABLE . " WHERE email = \"$mail\"");
        return $result ? $result->fetch()['cnt'] : 0;
    }

    /**
     * @param User $user
     * @return bool
     */
    public static function addUser(User &$user): bool
    {
        $stmt = DBSingleton::PDO()->prepare("
            INSERT INTO ".self::TABLE." (email, surname, name, password, age) 
            VALUES (:email, :firstname, :lastname, :password, :age)
        ");

        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':firstname', $user->getFirstname());
        $stmt->bindValue(':lastname', $user->getLastname());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':age', $user->getAge());

        $result = $stmt->execute();
        $user->setId(DBSingleton::PDO()->lastInsertId());
        if($result) {
            $resultRole = UserRoleManager::addUserRole($user, RoleManager::getRoleByName(RoleManager::ROLE_USER));
        }
        return $result && $resultRole;
    }

    /**
     * @param User $user
     * @return bool
     */
    public static function editUserByAdmin(User $user) : bool
    {
        $stmt = DBSingleton::PDO()->prepare("
        UPDATE user SET name = :name, surname = :surname, age = :age, email = :email
        WHERE id = :id
        ");

        $stmt->bindValue(':name', $user->getLastname());
        $stmt->bindValue(':surname', $user->getFirstname());
        $stmt->bindValue(':age', $user->getAge());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':id', $user->getId());

        $result = $stmt->execute();
        if ($result) {
            $rolesNames = [];
            foreach ($user->getRoles() as $role) {
                /* @var Role $role */
                $rolesNames[] = $role->getRoleName();
            }

            $resultRole = true;

            if (in_array('user', $rolesNames)) {
                $resultRole = UserRoleManager::addUserRole($user, RoleManager::getRoleByName(RoleManager::ROLE_USER));
            }
            else {
                $resultRole = UserRoleManager::removeUserRole($user, RoleManager::getRoleByName(RoleManager::ROLE_USER));
            }

            if (in_array('editor', $rolesNames)) {
                $resultRole = UserRoleManager::addUserRole($user, RoleManager::getRoleByName(RoleManager::ROLE_EDITOR));
            }
            else {
                $resultRole = UserRoleManager::removeUserRole($user, RoleManager::getRoleByName(RoleManager::ROLE_EDITOR));
            }

            if (in_array('admin', $rolesNames)) {
                $resultRole = UserRoleManager::addUserRole($user, RoleManager::getRoleByName(RoleManager::ROLE_ADMIN));
            }
            else {
                $resultRole = UserRoleManager::removeUserRole($user, RoleManager::getRoleByName(RoleManager::ROLE_ADMIN));
            }
        }

        return $result && $resultRole;
    }

    /**
     * @param User $user
     * @return bool
     */
    public static function editUserByUser(User $user) : bool
    {
        $stmt = DBSingleton::PDO()->prepare("
        UPDATE ". self::TABLE ." SET name = :name, surname = :surname, age = :age, email = :email
        WHERE id = :id
        ");

        $stmt->bindValue(':name', $user->getLastname());
        $stmt->bindValue(':surname', $user->getFirstname());
        $stmt->bindValue(':age', $user->getAge());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':id', $user->getId());

        return $stmt->execute();
    }


    /**
     * @param User $user
     * @param string $password
     * @return bool
     */
    public static function changePassword (User $user, string $password) : bool
    {
        $stmt = DBSingleton::PDO()->prepare("
        UPDATE user SET password = :password
        WHERE id = :id
        ");

        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':id', $user->getId());

        return $stmt->execute();
    }

    /**
     * Fetch a user by mail
     * @param string $mail
     * @return User|null
     */
    public static function getUserByMail(string $mail): ?User
    {
        $stmt = DBSingleton::PDO()->prepare("SELECT * FROM " . self::TABLE . " WHERE email = :mail LIMIT 1");
        $stmt->bindParam(':mail', $mail);
        return $stmt->execute() ? self::hydrateUser($stmt->fetch()) : null;
    }
}