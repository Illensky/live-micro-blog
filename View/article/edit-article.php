<?php

use App\Model\Entity\Article;

$article = $data['article'];
/* @var Article $article */
?>
<form action="/index.php?c=article&m=save-article-edit&id=<?= $article->getId() ?>" method="post">
    <div>
        <label for="title">Titre de l'article</label>
        <input type="text" name="title" id="title" value="<?= $article->getTitle() ?>">
    </div>
    <div>
        <textarea name="content" id="content" cols="30" rows="20"><?= $article->getContent() ?></textarea>
    </div>

    <input type="submit" name="save" value="Enregistrer">
</form>