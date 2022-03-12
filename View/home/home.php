<h1 class="main-title">Welcome to the Illensky's Blog</h1>

<?php

use App\Model\Entity\Article;

foreach ($data['articles'] as $article) {
    /* @var Article $article */
    ?>

    <div class="article-preview-div">
        <h1 class="article-title"><a href="/index.php?c=article&m=show-article&id=<?= $article->getId() ?>"><?= $article->getTitle() ?></a></h1>
        <div class="article-content-div">
            <p class="article-content-par"><?= substr($article->getContent(), 0, 155) . " ..." ?></p>
        </div>
        <div class="article-sign-div">
            <p class="article-sign-par"><?= $article->getAuthor()->getFirstname() ?> <?= $article->getAuthor()->getLastname() ?>
                , <?= $article->getDateUpdate()->format('Y-m-d H:i:s') ?></p>
        </div>
    </div>
    <?php
}
?>