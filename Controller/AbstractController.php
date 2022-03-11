<?php

use App\Model\Entity\Role;

abstract class AbstractController
{
    abstract public function index();

    /**
     * @param string $template
     * @param array $data
     * @return void
     */


    public function render(string $template, array $data = [])
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
    public function isFormSubmitted(): bool
    {
        return isset($_POST['save']);
    }


    /**
     * Return a form field value or default
     * @param string $field
     * @param $default
     * @return void
     */
    public function getFormField(string $field, $default = null)
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
    public function redirectIfNotConnected(): void
    {
        if(!self::isUserConnected()) {
            $this->render('home/home');
            exit();
        }
    }

    /**
     * @return void
     */
    public function redirectIfConnected(): void
    {
        if(self::isUserConnected()) {
            $this->render('home/home');
            exit();
        }
    }


    /**
     * @param string $role
     * @return void
     */
    public function redirectIfNotGranted(string $role): void
    {
        if(!self::isUserConnected()) {
            $this->render('home/home');
            exit();
        }

        $userRoles = array_map(function(Role $role2){
            return $role2->getRoleName();
        }, ($_SESSION['user'])->getRoles());

        if(!in_array($role, $userRoles)) {
            $this->render('home/home');
            exit();
        }
    }

}