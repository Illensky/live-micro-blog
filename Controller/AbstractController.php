<?php

use App\Model\Entity\Role;
use App\Model\Entity\user;

abstract class AbstractController
{
    abstract static public function index();

    /**
     * @param string $template
     * @param array $data
     * @return void
     */


    public static function render(string $template, array $data = [])
    {
        ob_start();
        require __DIR__ . '/../View/' . $template . '.php';
        $html = ob_get_clean();
        require __DIR__ . '/../View/base.php';
    }

    /**
     * Return true if a form were submitted.
     * @return bool
     */
    public static function isFormSubmitted(): bool
    {
        return isset($_POST['save']);
    }


    /**
     * Return a form field value or default
     * @param string $field
     * @param $default
     * @return void
     */
    public static function getFormField(string $field, $default = null)
    {
        if (!isset($_POST[$field])) {
            return (null === $default) ? '' : $default;
        }

        return $_POST[$field];
    }


    /**
     * @return bool
     */
    public static function isUserConnected(): bool
    {
        return isset($_SESSION['user']) && null !== ($_SESSION['user'])->getId();
    }


    /**
     * @return void
     */
    public static function redirectIfNotConnected(): void
    {
        if (!self::isUserConnected()) {
            HomeController::index();
            exit();
        }
    }

    /**
     * @return void
     */
    public static function redirectIfConnected(): void
    {
        if (self::isUserConnected()) {
            HomeController::index();
            exit();
        }
    }


    /**
     * @param string $role
     * @return void
     */
    public static function redirectIfNotGranted(string $role): void
    {
        if (!self::isUserConnected()) {
            HomeController::index();
            exit();
        }

        $userRoles = array_map(function (Role $role2) {
            return $role2->getRoleName();
        }, ($_SESSION['user'])->getRoles());

        if (!in_array($role, $userRoles)) {
            HomeController::index();
            exit();
        }
    }

    /**
     * @param user $user
     * @return array
     */
    public static function getUserRolesNames(User $user): array
    {
        $roles = [];
            foreach ($user->getRoles() as $role) {
                $roles[] = $role->getRoleName();
            }
        return $roles;
    }
}