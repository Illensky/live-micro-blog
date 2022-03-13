<?php


use App\Model\Manager\ArticleManager;
use App\Model\Manager\CommentManager;

class CommentController extends AbstractController
{

    static public function index()
    {
        HomeController::index();
    }

    static public function addComment (int $articleId) : void
    {
        self::redirectIfNotGranted('user');
        if (self::isFormSubmitted()) {
            $user = $_SESSION['user'];
            $article = ArticleManager::getArticleById($articleId);
            $content = filter_var(self::getFormField('content'), FILTER_SANITIZE_STRING);

            CommentManager::addComment($user, $article, $content);
            $_SESSION['success'] = "Votre commentaire as bien etait poster";
        }
        else {
            $_SESSION['error'][] = "Erreur : formulaire manquant";
        }

        ArticleController::showArticle($articleId);
    }

    public static function adminDeleteComment (int $id) : void
    {
        self::redirectIfNotGranted('admin');
        $comment = CommentManager::getCommentById($id);
        CommentManager::deleteComment($comment);
        $_SESSION['success'] = "Le commentaire as bien etait suprimmer";
        UserController::showUser($comment->getAuthor()->getId());
    }

    static public function deleteComment (int $id) : void
    {
        self::redirectIfNotGranted('user');
        $comment = CommentManager::getCommentById($id);
        if ($_SESSION['user']->getId() === $comment->getAuthor()->getId()) {
            CommentManager::deleteComment($comment);
            $_SESSION['success'] = "Votre commentaire as bien etait suprimmer";
        }
        else {
            $_SESSION['error'][] = "Erreur : Vous n'etes pas l'auteur de ce commentaire";
        }
        ArticleController::showArticle($comment->getArticle()->getId());
    }

    static public function editComment (int $id) : void
    {
        self::redirectIfNotGranted('user');
        $comment = CommentManager::getCommentById($id);
        if ($_SESSION['user']->getId() === $comment->getAuthor()->getId()) {
            self::render('comment/edit-comment', [
                'comment' => $comment
            ]);
            exit();
        }
        else {
            $_SESSION['error'][] = "Erreur : Vous n'etes pas l'auteur de ce commentaire";
        }
        ArticleController::showArticle($comment->getArticle()->getId());
    }

    static public function saveCommentEdit (int $id) : void
    {
        self::redirectIfNotGranted('user');
        $comment = CommentManager::getCommentById($id);
        if (!$_SESSION['user']->getId() === $comment->getAuthor()->getId()) {
            $_SESSION['error'][] = "Erreur : Vous n'êtes pas l'auteur de ce commentaire";
        }
        if (self::isFormSubmitted()) {
            $content = filter_var(self::getFormField('content'), FILTER_SANITIZE_STRING);
            CommentManager::editComment($comment, $content);
            $_SESSION['success'] = "Votre commentaire as bien était modifié";
        }
        else {
            $_SESSION['error'][] = "Erreur : Un formulaire est manquant";
        }

        ArticleController::showArticle($comment->getArticle()->getId());
    }
}