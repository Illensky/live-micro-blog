<?php
/*@var User $user */
$user = $data['user'];

?>
<h1>Détails de l'utilisateur</h1>


<p>ID: <?= $user->getId() ?></p>
<p>Email: <?= $user->getEmail() ?></p>
<p>Firstname: <?= $user->getFirstname() ?></p>
<p>Lastname: <?= $user->getLastname() ?></p>
<p>Password: <?= $user->getPassword() ?></p>
<p>Age: <?= $user->getAge() ?></p>

<div>
<?php
    foreach ($user->getRoles() as $role) { ?>
        <p><strong>Available roles:</strong></p>
        <p><?= $role->getRoleName() ?></p>
        <?php
    } ?>
</div>
