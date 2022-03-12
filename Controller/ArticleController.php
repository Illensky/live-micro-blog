<?php

use App\Model\Entity\Article;
use App\Model\Manager\ArticleManager;
use App\Model\Manager\UserManager;

class ArticleController extends AbstractController
{

    public function index()
    {
        // mon commentaire qui m'a pris du temsp à écrire.
    }

    public function listAllArticles()
    {

    }

    /**
     * Route to add a new article.
     * @return void
     */
    public function addArticle()
    {
        $this->redirectIfNotGranted('editor');

        if($this->isFormSubmitted()) {
            // Admettons que ce user ait été pris depuis la session.
            dump($_SESSION['user']);
            $user = $_SESSION['user'];

            // Getting Article data from form.
            $title = filter_var($this->getFormField('title'), FILTER_SANITIZE_STRING);
            $content = filter_var($this->getFormField('content'), FILTER_SANITIZE_STRING);

            // Create a new Article entity (no persisted).
            $article = new Article();
            $article
                ->setTitle($title)
                ->setContent($content)
                ->setAuthor($user)
            ;

            // Saving new article.
            if(ArticleManager::addNewArticle($article)) {
                $this->render('article/show-article', [
                    'article' => $article,
                ]);
                exit();
            }
        }

        $this->render('article/add-article');
    }
}























