<?php

use App\Model\Entity\Comment;

$comment = $data['comment'];
/* @var Comment $comment */

?>

<form action="/index.php?c=comment&m=save-comment-edit&id=<?= $comment->getId() ?>" method="post">
    <div>
        <textarea name="content" id="content" cols="30" rows="10"><?= $comment->getContent() ?></textarea>
    </div>
    <div>
        <input type="submit" value="Sauvegarder" name="save">
    </div>
</form>
