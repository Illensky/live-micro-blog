<?php

namespace App\Model\Manager;

use App\Model\Entity\Article;
use App\Model\Entity\Comment;
use App\Model\DBSingleton;
use App\Model\Entity\user;

final class CommentManager
{
    public const TABLE = 'comments';

    public static function getCommentsByArticle(Article $article): array
    {
        $comments = [];

        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM " . self::TABLE . " 
            WHERE article_fk = " . $article->getId()
        );

        if ($query) {
            foreach ($query->fetchAll() as $commentData) {
                $comments[] = (new Comment())
                    ->setId($commentData['id'])
                    ->setContent($commentData['content'])
                    ->setAuthor(UserManager::getUserById($commentData['user_fk']))
                    ->setArticle(ArticleManager::getArticleById($commentData['article_fk']));
            }
        }

        return $comments;
    }

    public static function getCommentsByUser(User $user): array
    {
        $comments = [];

        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM " . self::TABLE . " 
            WHERE user_fk = " . $user->getId()
        );

        if ($query) {
            foreach ($query->fetchAll() as $commentData) {
                $comments[] = (new Comment())
                    ->setId($commentData['id'])
                    ->setContent($commentData['content'])
                    ->setAuthor(UserManager::getUserById($commentData['user_fk']))
                    ->setArticle(ArticleManager::getArticleById($commentData['article_fk']));
            }
        }

        return $comments;
    }

    public static function addComment(User $user, Article $article, string $content)
    {
        $stmt = DBSingleton::PDO()->prepare("
            INSERT INTO " . self::TABLE . " (content, user_fk, article_fk) 
            VALUES (:content, :author, :article)
        ");

        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':author', $user->getId());
        $stmt->bindValue(':article', $article->getId());

        return $stmt->execute();
    }

    public static function getCommentById(int $id): Comment
    {
        $comment = new Comment();

        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM " . self::TABLE . " 
            WHERE id = " . $id
        );

        if ($query) {
            $commentData = $query->fetch();
            $comment
                ->setId($commentData['id'])
                ->setContent($commentData['content'])
                ->setAuthor(UserManager::getUserById($commentData['user_fk']))
                ->setArticle(ArticleManager::getArticleById($commentData['article_fk']))
            ;
        }

        return $comment;
    }

    public static function commentExist (Comment $comment) : bool
    {
        $result = DBSingleton::PDO()->query("
        SELECT count(*) as cnt FROM " . self::TABLE . " 
        WHERE id = ".$comment->getId()
        );
        return $result ? $result->fetch()['cnt'] : 0;
    }

    public static function deleteComment (Comment $comment) : bool
    {
        if (self::commentExist($comment)) {
            return (bool)DBSingleton::PDO()->query("
            DELETE FROM " . self::TABLE . " 
            WHERE id = " . $comment->getId()
            );
        }
        return false;
    }

    public static function editComment (Comment $comment, string $content) : bool
    {
        $stmt = DBSingleton::PDO()->prepare("
        UPDATE ". self::TABLE ." SET content = :content
        WHERE id = :id
        ");

        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':id', $comment->getId());

        return $stmt->execute();
    }
}