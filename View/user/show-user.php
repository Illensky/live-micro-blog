<?php

use App\Model\Entity\Article;
use App\Model\Entity\Comment;
use App\Model\Entity\user;
use App\Model\Manager\ArticleManager;
use App\Model\Manager\CommentManager;

$user = $data['user'];
/* @var User $user */
?>
<h1>Détails de l'utilisateur</h1>


<p>ID: <?= $user->getId() ?></p>
<p>Email: <?= $user->getEmail() ?></p>
<p>Prénom: <?= $user->getFirstname() ?></p>
<p>Nom: <?= $user->getLastname() ?></p>
<p>Age: <?= $user->getAge() ?></p>

<div>
    <p><strong>Ses roles:</strong></p>
    <?php
    foreach ($user->getRoles() as $role) { ?>

        <p><?= $role->getRoleName() ?></p>
        <?php
    } ?>
</div>

<h3>Ses articles</h3>
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
            <a href="/index.php?c=article&m=admin-delete-article&id=<?= $article->getId() ?>">Supprimer</a>
        </li>
        <?php
    }
    ?>
</ul>
<h3>Ses commentaires</h3>
<ul>
    <?php
    foreach (CommentManager::getCommentsByUser($user) as $comment) {
        /* @var Comment $comment */
        ?>
        <li>
            <a href="/index.php?c=article&m=show-article&id=<?= $comment->getArticle()->getId() ?>">
                "<?= substr($comment->getContent(), 0, 30) . " ..." ?>" sur l'article <?= $comment->getArticle()->getTitle() ?>
            </a>
            <a href="/index.php?c=comment&m=admin-delete-comment&id=<?= $comment->getId() ?>">Supprimer</a>
        </li>
        <?php
    }
    ?>
</ul>
