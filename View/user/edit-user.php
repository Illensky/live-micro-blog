<?php

use App\Model\Entity\user;
use App\Model\Manager\UserManager;

$user = $data['user'];
/* @var User $user */
?>

<form action="/index.php?c=user&m=save-user-edit-by-admin&id=<?= $user->getId() ?>" method="post">
    <div>
        <label for="email">E-mail</label>
        <input type="text" name="email" id="email" value="<?= $user->getEmail() ?>">
    </div>
    <div>
        <label for="firstname">First name</label>
        <input type="text" name="firstname" id="firstname" value="<?= $user->getFirstname() ?>">
    </div>
    <div>
        <label for="lastname">Last name</label>
        <input type="text" name="lastname" id="lastname" value="<?= $user->getLastname() ?>">
    </div>
    <div>
        <label for="age">Age</label>
        <input type="number" name="age" id="age" value="<?= $user->getAge() ?>">
    </div>
    <div>
        <h3>Roles : </h3>
        <label for="user">User</label>
        <input type="checkbox" id="user" name="user"
            <?php
            $roles = UserController::getUserRolesNames($user);
            if (in_array("user", $roles)) {
                ?>
            checked
            <?php
            }
            ?>
        >

        <label for="editor">Editor</label>
        <input type="checkbox" id="editor" name="editor"
            <?php
            if (in_array("editor", $roles)) {
                ?>
                checked
                <?php
            }
            ?>
        >

        <label for="admin">Admin</label>
        <input type="checkbox" id="admin" name="admin"
            <?php
            if (in_array("admin", $roles)) {
                ?>
                checked
                <?php
            }
            ?>
        >

    </div>

    <input type="submit" value="Sauvegarder" name="save">
</form>
