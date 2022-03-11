<?php

use App\Model\Entity\user;

?>
<h1>Liste des utilisateurs</h1>
<p>Statistiques sur les utilisateurs, <a href="/index.php?c=user&m=show-stats">cliquez ici</a></p>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Détails</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($data['users_list'] as $user) {
        /* @var User $user */
        ?>
        <tr>
            <td><?= $user->getId() ?></td>
            <td><?= $user->getFirstname() ?></td>
            <td><?= $user->getLastname() ?></td>
            <td>
                <a href="/index.php?c=user&m=show-user&id=<?= $user->getId() ?>">Voir plus</a>
            </td>
            <td>
                <a href="/index.php?c=user&m=edit-user&id=<?= $user->getId() ?>">Edit</a>
            </td>
            <td>
                <a href="/index.php?c=user&m=delete-user&id=<?= $user->getId() ?>">Delete</a>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>