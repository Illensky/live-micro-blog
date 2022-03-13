<?php

namespace App\Model\Manager;

use App\Model\Entity\Article;
use App\Model\DBSingleton;
use App\Model\Entity\user;
use DateTime;

final class ArticleManager
{
    public const TABLE = 'article';


    /**
     * @param user $user
     * @return array
     */
    public static function getArticlesByUser(User $user) : array
    {
        $articles = [];

        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM " . self::TABLE . " 
            WHERE user_fk = " . $user->getId()
        );

        if ($query) {
            $format = 'Y-m-d H:i:s';

            foreach ($query->fetchAll() as $articleData) {
                $articles[] = (new Article())
                    ->setId($articleData['id'])
                    ->setAuthor(UserManager::getUserById($articleData['user_fk']))
                    ->setContent($articleData['content'])
                    ->setDateAdd(DateTime::createFromFormat($format, $articleData['date_add']))
                    ->setDateUpdate(DateTime::createFromFormat($format, $articleData['date_update']))
                    ->setTitle($articleData['title'])
                ;
            }
        }

        return $articles;
    }

    public static function getAll() : array
    {
        $articles = [];
        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM " . self::TABLE
        );

        if ($query) {
            $format = 'Y-m-d H:i:s';

            foreach ($query->fetchAll() as $articleData) {
                $articles[] = (new Article())
                    ->setId($articleData['id'])
                    ->setAuthor(UserManager::getUserById($articleData['user_fk']))
                    ->setContent($articleData['content'])
                    ->setDateAdd(DateTime::createFromFormat($format, $articleData['date_add']))
                    ->setDateUpdate(DateTime::createFromFormat($format, $articleData['date_update']))
                    ->setTitle($articleData['title'])
                    ;
            }
        }
        return $articles;
    }


    /**
     * @param int $articleId
     * @return Article
     */
    public static function getArticleById (int $articleId) : Article
    {
        $article = new Article();

        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM " . self::TABLE . " WHERE id = " . $articleId
        );

        if ($query) {
            $articleData = $query->fetch();
            $format = 'Y-m-d H:i:s';
            $article
                ->setId($articleData['id'])
                ->setAuthor(UserManager::getUserById($articleData['user_fk']))
                ->setContent($articleData['content'])
                ->setDateAdd(DateTime::createFromFormat($format, $articleData['date_add']))
                ->setDateUpdate(DateTime::createFromFormat($format, $articleData['date_update']))
                ->setTitle($articleData['title'])
            ;
        }

        return $article;
    }

    /**
     * Add a new article into the db.
     * @param Article $article
     * @return void
     */
    public static function addNewArticle(Article &$article): bool
    {
        $stmt = DBSingleton::PDO()->prepare("
            INSERT INTO ". self::TABLE ." (title, content, user_fk) VALUES (:title, :content, :author)
        ");

        $stmt->bindValue(':title', $article->getTitle());
        $stmt->bindValue(':content', $article->getContent());
        $stmt->bindValue(':author', $article->getAuthor()->getId());

        $result = $stmt->execute();
        $article->setId(DBSingleton::PDO()->lastInsertId());
        return $result;
    }

    public static function articleExist (Article $article) : bool
    {
        $result = DBSingleton::PDO()->query("
        SELECT count(*) as cnt FROM " . self::TABLE . " 
        WHERE id = ".$article->getId()
        );
        return $result ? $result->fetch()['cnt'] : 0;
    }

    public static function deleteArticle(Article $article): bool
    {
        if (self::articleExist($article)) {
            return (bool)DBSingleton::PDO()->query("
            DELETE FROM " . self::TABLE . " 
            WHERE id = " . $article->getId()
            );
        }
        return false;
    }

    public static function editArticle (Article $article, string $content, string $title) : bool
    {
        $stmt = DBSingleton::PDO()->prepare("
        UPDATE ". self::TABLE ." SET content = :content, title = :title
        WHERE id = :id
        ");

        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':id', $article->getId());

        return $stmt->execute();
    }
}