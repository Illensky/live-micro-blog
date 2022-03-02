<?php

namespace App\Model;

use Config;
use PDO;
use PDOException;

class DBSingleton
{
    private static ?PDO $pdoObject = null;
    private static string $dsn = "mysql:host=%s;dbname=%s;charset=%s";

    /**
     * @return PDO
     */
    public static function PDO(): PDO
    {
        if (self::$pdoObject === null) {
            try {
                $dsn = sprintf(self::$dsn, Config::DB_HOST, Config::DB_NAME, Config::DB_CHARSET);
                self::$pdoObject = new PDO($dsn, Config::DB_USERNAME, Config::DB_PASSWORD);
                self::$pdoObject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdoObject->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
            catch (PDOException $e) {
                die();
            }
        }
        return self::$pdoObject;
    }
}