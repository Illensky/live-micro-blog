<?php use App\Model\Entity\Article;
/* @var Article $article */
$article = $data['article'];
?>
<div class="article-div">
    <h1 class="article-title"><?= $article->getTitle() ?></h1>
    <div class="article-content-div">
        <p class="article-content-par"><?= $article->getContent() ?></p>
    </div>
    <div class="article-sign-div">
        <p class="article-sign-par"><?= $article->getAuthor()->getFirstname() ?> <?= $article->getAuthor()->getLastname() ?>, <?= $article->getDateUpdate()->format('Y-m-d H:i:s') ?></p>
    </div>

</div>

