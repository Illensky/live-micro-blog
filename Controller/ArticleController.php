<?php

use App\Model\Entity\Article;
use App\Model\Manager\ArticleManager;
use App\Model\Manager\UserManager;

class ArticleController extends AbstractController
{

    public static function index()
    {
        // mon commentaire qui m'a pris du temsp à écrire.
    }

    public static function listAllArticles()
    {
        $articles = ArticleManager::getAll();
        self::render("home/home", [
            "articles" => $articles
        ]);
    }

    /**
     * Route to add a new article.
     * @return void
     */
    public static function addArticle()
    {
        self::redirectIfNotGranted('editor');

        if (self::isFormSubmitted()) {
            // Admettons que ce user ait été pris depuis la session.
            $user = $_SESSION['user'];

            // Getting Article data from form.
            $title = filter_var(self::getFormField('title'), FILTER_SANITIZE_STRING);
            $content = filter_var(self::getFormField('content'), FILTER_SANITIZE_STRING);

            // Create a new Article entity (no persisted).
            $article = new Article();
            $actualDate = new DateTime();
            $article
                ->setTitle($title)
                ->setContent($content)
                ->setAuthor($user)
                ->setDateAdd($actualDate)
                ->setDateUpdate($actualDate);

            // Saving new article.
            if (ArticleManager::addNewArticle($article)) {
                self::render('article/show-article', [
                    'article' => $article,
                ]);
                exit();
            }
        }

        self::render('article/add-article');
    }

    /**
     * @param int $id
     */
    public static function showArticle (int $id) : void
    {
        self::redirectIfNotConnected();
        $article = ArticleManager::getArticleById($id);
        self::render('article/show-article', [
            'article' => $article,
        ]);
        exit();
    }

    public static function adminDeleteArticle (int $id) : void
    {
        self::redirectIfNotGranted('admin');
        $article = ArticleManager::getArticleById($id);
        ArticleManager::deleteArticle($article);
        $_SESSION['success'] = "L'article as bien etait suprimmer";
        UserController::showUser($article->getAuthor()->getId());
    }

    public static function deleteArticle (int $id) : void
    {
        self::redirectIfNotGranted('user');
        $article = ArticleManager::getArticleById($id);
        if ($_SESSION['user']->getId() === $article->getAuthor()->getId()) {
            ArticleManager::deleteArticle($article);
            $_SESSION['success'] = "Votre article as bien etait suprimmer";
        }
        else {
            $_SESSION['error'][] = "Erreur : Vous n'etes pas l'auteur de cet article";
        }
        HomeController::index();
    }

    public static function editArticle (int $id) : void
    {
        self::redirectIfNotGranted('user');
        $article = ArticleManager::getArticleById($id);
        if ($_SESSION['user']->getId() === $article->getAuthor()->getId()) {
            self::render('article/edit-article', [
                'article' => $article
            ]);
            exit();
        }
        else {
            $_SESSION['error'][] = "Erreur : Vous n'etes pas l'auteur de ce commentaire";
        }
        self::showArticle($article->getId());
    }

    public static function saveArticleEdit (int $id) : void
    {
        self::redirectIfNotGranted('user');
        $article = ArticleManager::getArticleById($id);
        if (!$_SESSION['user']->getId() === $article->getAuthor()->getId()) {
            $_SESSION['error'][] = "Erreur : Vous n'êtes pas l'auteur de cet article";
        }
        if (self::isFormSubmitted()) {
            $content = filter_var(self::getFormField('content'), FILTER_SANITIZE_STRING);
            $title = filter_var(self::getFormField('title'), FILTER_SANITIZE_STRING);
            ArticleManager::editArticle($article, $content, $title);
            $_SESSION['success'] = "Votre article as bien était modifié";
        }
        else {
            $_SESSION['error'][] = "Erreur : Un formulaire est manquant";
        }
        self::showArticle($article->getId());
    }
}























