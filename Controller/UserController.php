<?php


use App\Model\Entity\user;
use App\Model\Manager\RoleManager;
use App\Model\Manager\UserManager;

class UserController extends AbstractController
{
    /**
     * UserController entry point - default action.
     */
    public static function index()
    {
        self::redirectIfNotGranted('admin');
        self::render('user/users-list', [
            'users_list' => UserManager::getAll()
        ]);
    }

    /**
     * Fetch and display some users statistics.
     * @return void
     */
    public static function showStats()
    {
        self::redirectIfNotGranted('admin');
        self::render('user/statistics', [
            'users_count' => UserManager::getUsersCount(),
            'min_age' => UserManager::getMinAge()
        ]);
    }


    /**
     * Display a specific user information.
     * @param int $id
     * @return void
     */
    public static function showUser(int $id)
    {
        self::redirectIfNotGranted('admin');
        if (UserManager::userExists($id)) {
            $user = UserManager::getUserById($id);
            $user->setPassword('');

            self::render('user/show-user', [
                'user' => $user
            ]);
        } else {
            self::index();
        }
    }


    // TODO
    public static function editUser(int $id)
    {
        self::redirectIfNotGranted('admin');

        if (UserManager::userExists($id)) {
            $user = UserManager::getUserById($id);
            $user->setPassword('');

            self::render('user/edit-user', [
                'user' => $user
            ]);
        } else {
            self::index();
        }
    }


    /**
     * Route handling users deletion.
     * @param int $id
     * @return void
     */
    public static function deleteUser(int $id)
    {
        self::redirectIfNotGranted('admin');
        if (UserManager::userExists($id)) {
            $user = UserManager::getUserById($id);
            $deleted = UserManager::deleteUser($user);
        }
        self::index();
    }

    /**
     * @return void
     */
    public static function register()
    {
        self::redirectIfConnected();

        if (self::isFormSubmitted()) {
            $mail = filter_var(self::getFormField('email'), FILTER_SANITIZE_EMAIL);
            $firstname = filter_var(self::getFormField('firstname'), FILTER_SANITIZE_STRING);
            $lastname = filter_var(self::getFormField('lastname'), FILTER_SANITIZE_STRING);
            $password = self::getFormField('password');
            $passwordRepeat = self::getFormField('password-repeat');
            $age = (int)self::getFormField('age');

            $errors = [];
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                // l'email n'est pas valide.
                $errors[] = "L'adresse mail n'est pas au bon format";
            }

            if (!strlen($firstname) >= 2) {
                // Le firstname ne fait pas au moins 2 caractères.
                $errors[] = "Le firstname ne fait pas au moins 2 chars";
            }

            if (!strlen($lastname) >= 2) {
                // Le lastname ne fait pas au moins 2 caractères.
                $errors[] = "Le lastname ne fait pas au moins 2 chars";
            }

            if ($password !== $passwordRepeat) {
                // Les passwords ne correspondent pas !
                $errors[] = "Les password ne correspondent pas";
            }

            if (!preg_match('/^(?=.*[!@#$%^&*-\])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/', $password)) {
                // Le password ne correspond pas au critère.
                $errors[] = "Le password ne correpsond pas au critère";
            }

            if ($age <= 18 || $age >= 120) {
                // L'age n'est pas dans la bonne tranche.
                $errors[] = "L'age n'est pas réglementaire";
            }

            // S'il y a une erreur, enregistrement des messages en session.
            if (count($errors) > 0) {
                $_SESSION['errors'] = $errors;
            } else {
                // C'est ok, pas d'erreurs, enregistrement.

                $role[] = RoleManager::getRoleByName('user');
                $user = (new User())
                    ->setAge($age)
                    ->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setEmail($mail)
                    ->setPassword(password_hash($password, PASSWORD_DEFAULT))
                    ->setRoles($role);

                if (!UserManager::userMailExists($user->getEmail())) {
                    UserManager::addUser($user);
                    if (null !== $user->getId()) {
                        $_SESSION['success'] = "Félicitations votre compte est actif";
                        $user->setPassword('');
                        $_SESSION['user'] = $user;
                        // TODO Envoyer un mail à l'utilisateur pour vérifier l'adresse mail.
                    } else {
                        $_SESSION['errors'] = ["Impossible de vous enregistrer"];
                    }
                } else {
                    $_SESSION['errors'] = ["Cette adresse mail existe déjà !"];
                }
            }

        }
        self::render('user/register');
    }


    /**
     * User logout.
     * @return void
     */
    public static function logout(): void
    {
        if (self::isUserConnected()) {
            $_SESSION = [];
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            session_destroy();
        }

        HomeController::index();
    }


    /**
     * User login
     * @return void
     */
    public static function login()
    {
        self::redirectIfConnected();

        if (self::isFormSubmitted()) {
            $errorMessage = "L'utilisateur / le password est mauvais";
            $mail = filter_var(self::getFormField('email'), FILTER_SANITIZE_STRING);
            $password = self::getFormField('password');

            $user = UserManager::getUserByMail($mail);
            if (null === $user) {
                $_SESSION['errors'][] = $errorMessage;
            } else {
                if (password_verify($password, $user->getPassword())) {
                    $user->setPassword('');
                    $_SESSION['user'] = $user;
                    self::redirectIfConnected();
                    exit();
                } else {
                    $_SESSION['errors'][] = $errorMessage;
                }
            }
        }

        self::render('user/login');
    }

    public static function saveUserEditByAdmin(int $id): void
    {
        self::redirectIfNotGranted('admin');

        if (self::isFormSubmitted()) {
            $mail = filter_var(self::getFormField('email'), FILTER_SANITIZE_EMAIL);
            $firstname = filter_var(self::getFormField('firstname'), FILTER_SANITIZE_STRING);
            $lastname = filter_var(self::getFormField('lastname'), FILTER_SANITIZE_STRING);
            $age = (int)self::getFormField('age');
            $roleUser = boolval(self::getFormField('user'));
            $roleEditor = boolval(self::getFormField('editor'));
            $roleAdmin = boolval(self::getFormField('admin'));

            $roles = [];

            if ($roleUser) {
                $roles[] = RoleManager::getRoleByName('user');
            }

            if ($roleEditor) {
                $roles[] = RoleManager::getRoleByName('editor');
            }

            if ($roleAdmin) {
                $roles[] = RoleManager::getRoleByName('admin');
            }

            $errors = [];
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                // l'email n'est pas valide.
                $errors[] = "L'adresse mail n'est pas au bon format";
            }

            if (!strlen($firstname) >= 2) {
                // Le firstname ne fait pas au moins 2 caractères.
                $errors[] = "Le firstname ne fait pas au moins 2 chars";
            }

            if (!strlen($lastname) >= 2) {
                // Le lastname ne fait pas au moins 2 caractères.
                $errors[] = "Le lastname ne fait pas au moins 2 chars";
            }

            if ($age <= 18 || $age >= 120) {
                // L'age n'est pas dans la bonne tranche.
                $errors[] = "L'age n'est pas réglementaire";
            }

            // S'il y a une erreur, enregistrement des messages en session.
            if (count($errors) > 0) {
                $_SESSION['errors'] = $errors;
            } else {
                // C'est ok, pas d'erreurs, enregistrement des modifications.
                $user = (new User())
                    ->setAge($age)
                    ->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setEmail($mail)
                    ->setRoles($roles)
                    ->setId($id);

                UserManager::editUserByAdmin($user);
                    $_SESSION['success'] = "Succés, l'utilisateur as bien était modifié";
            }
        }
        self::index();
    }

    public static function saveUserEditByUser(): void
    {
        self::redirectIfNotGranted('user');

        if (self::isFormSubmitted()) {
            $mail = filter_var(self::getFormField('email'), FILTER_SANITIZE_EMAIL);
            $firstname = filter_var(self::getFormField('firstname'), FILTER_SANITIZE_STRING);
            $lastname = filter_var(self::getFormField('lastname'), FILTER_SANITIZE_STRING);
            $age = (int)self::getFormField('age');

            $errors = [];
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                // l'email n'est pas valide.
                $errors[] = "L'adresse mail n'est pas au bon format";
            }

            if (!strlen($firstname) >= 2) {
                // Le firstname ne fait pas au moins 2 caractères.
                $errors[] = "Le firstname ne fait pas au moins 2 chars";
            }

            if (!strlen($lastname) >= 2) {
                // Le lastname ne fait pas au moins 2 caractères.
                $errors[] = "Le lastname ne fait pas au moins 2 chars";
            }

            if ($age <= 18 || $age >= 120) {
                // L'age n'est pas dans la bonne tranche.
                $errors[] = "L'age n'est pas réglementaire";
            }

            // S'il y a une erreur, enregistrement des messages en session.
            if (count($errors) > 0) {
                $_SESSION['errors'] = $errors;
            } else {
                // C'est ok, pas d'erreurs, enregistrement des modifications.
                $user = (new User())
                    ->setAge($age)
                    ->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setEmail($mail)
                    ->setId($_SESSION['user']->getId());

                if (UserManager::editUserByUser($user)) {
                    $_SESSION['user']
                        ->setAge($age)
                        ->setFirstname($firstname)
                        ->setLastname($lastname)
                        ->setEmail($mail);
                    $_SESSION['success'] = "Succés, modifications bien prisent en compte";
                }
            }
        }
        self::userSpace();
    }

    public static function passwordChange(): void
    {
        self::redirectIfNotGranted('user');

        if (self::isFormSubmitted()) {
            if (password_verify(self::getFormField('actualPassword'), UserManager::getUserById($_SESSION['user']->getId())->getPassword())) {
                if (preg_match('/^(?=.*[!@#$%^&*-\])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/', self::getFormField('newPassword'))) {
                    if (UserManager::changePassword($_SESSION['user'], password_hash(self::getFormField('newPassword', PASSWORD_DEFAULT)))) {
                        $_SESSION['success'][] = "Succés, ton mot de passe as bien était modifier";
                    } else {
                        $_SESSION['errors'][] = "Erreur : Modification échoué";
                    }
                }
                else {
                    $_SESSION['errors'][] = "Erreur : Nouveau mot de passe invalide";
                }
            } else {
                $_SESSION['errors'][] = "Erreur : Mot de passe actuel invalide";
            }
        } else {
            $_SESSION['errors'][] = "Erreur : Absence du formulaire";
        }
        self::userSpace();
    }

    public static function userSpace(): void
    {
        self::render('user/user-space');
    }
}