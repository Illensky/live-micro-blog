<?php

use App\Model\Entity\Article;
use App\Model\Entity\Comment;
use App\Model\Manager\CommentManager;

/* @var Article $article */
$article = $data['article'];

$comments = CommentManager::getCommentsByArticle($article);

?>
<div class="article-div">
    <h1 class="article-title"><?= $article->getTitle() ?></h1>
    <div class="article-content-div">
        <p class="article-content-par"><?= $article->getContent() ?></p>
    </div>
    <div class="article-sign-div">
        <p class="article-sign-par"><?= $article->getAuthor()->getFirstname() ?> <?= $article->getAuthor()->getLastname() ?>
            , <?= $article->getDateUpdate()->format('Y-m-d H:i:s') ?></p>
    </div>
    <?php
    if ($_SESSION['user']->getId() === $article->getAuthor()->getId()) {
        ?>
        <div>
            <a href="/index.php?c=article&m=edit-article&id=<?= $article->getId() ?>">Editer</a>
            <a href="/index.php?c=article&m=delete-article&id=<?= $article->getId() ?>">supprimer</a>
        </div>
        <?php
    }
    ?>
</div>
<div id="comment-div">
    <h1>Commentaire</h1>
    <?php
    foreach ($comments as $comment) {
        /* @var Comment $comment */
        ?>
        <div>
            <h3><?= $comment->getAuthor()->getFirstname() ?> <?= $comment->getAuthor()->getLastname() ?></h3>
            <p><?= $comment->getContent() ?></p>
            <?php
            if ($_SESSION['user']->getId() === $comment->getAuthor()->getId()) {
                ?>
                <a href="/index.php?c=comment&m=edit-comment&id=<?= $comment->getId() ?>">Editer</a>
                <a href="/index.php?c=comment&m=delete-comment&id=<?= $comment->getId() ?>">supprimer</a>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>
<div id="add-comment-div">
    <form action="/index.php?c=comment&m=add-comment&articleId=<?= $article->getId() ?>" method="post">
        <label for="content">Votre commentaire :</label>
        <textarea name="content" id="content" cols="30" rows="10"></textarea>
        <input type="submit" value="Ajouter" name="save">
    </form>
</div>
