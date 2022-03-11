<?php

namespace App\Model\Manager;

use App\Model\DB;
use App\Model\Entity\User;
use App\Model\DBSingleton;

final class UserManager
{
    public const TABLE = 'user';
    public const TABLE_USER_ROLE = 'user_role';

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
            return DBSingleton::PDO()->exec("
            DELETE FROM " . self::TABLE . " WHERE id = {$user->getId()}
        ");
        }
        return false;
    }

    /**
     * Check if a user exists with its email.
     * @param int $id
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
            $role = RoleManager::getRoleByName(RoleManager::ROLE_USER);
            $resultRole = DBSingleton::PDO()->exec("
                INSERT INTO ".self::TABLE_USER_ROLE. " (user_fk, role_fk) VALUES (".$user->getId().", ".$role->getId().")
            ");

        }
        return $result && $resultRole;
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