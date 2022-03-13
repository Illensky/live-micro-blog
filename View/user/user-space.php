<?php

use App\Model\Entity\Article;
use App\Model\Entity\Comment;
use App\Model\Entity\user;
use App\Model\Manager\ArticleManager;
use App\Model\Manager\CommentManager;

$user = $_SESSION['user'];
 /* @var User $user */

?>
<h3>Editer mes infos</h3>
<form action="/index.php?c=user&m=save-user-edit-by-user" method="post">
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
    <input type="submit" value="Sauvegarder" name="save">
</form>
<h3>Modifier mon mot de passe</h3>
<form action="/index.php?c=user&m=password-change" method="post">
    <div>
        <label for="actualPassword">Mot de passe actuel</label>
        <input type="password" name="actualPassword" id="actualPassword">
    </div>
    <div>
        <label for="newPassword">Nouveau mot de passe</label>
        <input type="password" name="newPassword" id="newPassword">
    </div>
    <input type="submit" value="Sauvegarder" name="save">
</form>
<h3>Vos articles</h3>
<ul>
    <?php
    foreach (ArticleManager::getArticlesByUser($user) as $article) {
        /* @var Article $article */
    ?>
    <li>
        <a href="/index.php?c=article&m=show-article&id=<?= $article->getId() ?>">
            <?= $article->getTitle() ?>, du <?= $article->getDateAdd()->format('Y-m-d H:i:s') ?>,
            modifier le <?= $article->getDateUpdate()->format('Y-m-d H:i:s') ?>
        </a>
    </li>
    <?php
    }
    ?>
</ul>
<h3>Vos commentaires</h3>
<ul>
    <?php
    foreach (CommentManager::getCommentsByUser($user) as $comment) {
    /* @var Comment $comment */
    ?>
    <li>
        <a href="/index.php?c=article&m=show-article&id=<?= $comment->getArticle()->getId() ?>">
        "<?= substr($comment->getContent(), 0, 30) . " ..." ?>" sur l'article <?= $comment->getArticle()->getTitle() ?>
        </a>
    </li>
    <?php
    }
    ?>
</ul>