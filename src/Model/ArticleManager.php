<?php

namespace App\Model;

use PDO;

class ArticleManager extends AbstractManager
{
    public const TABLE = 'article';
    public const TABLE2 = 'category';
    public const TABLE3 = 'picture';
    /**
     * Insert new article in database
     */
    public function insert(array $article): void
    {
        $query = "INSERT INTO " . self::TABLE . " (title, extract, content, photo, category_id, author, date)
                VALUES (:title, :extract, :content, :photo, :category_id, :author, :date);";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':title', $article['title'], PDO::PARAM_STR);
        $statement->bindValue(':extract', $article['extract'], PDO::PARAM_STR);
        $statement->bindValue(':content', $article['content'], PDO::PARAM_STR);
        $statement->bindValue(':photo', $article['photo'], PDO::PARAM_STR);
        $statement->bindValue(':category_id', $article['category_id'], PDO::PARAM_STR);
        $statement->bindValue(':author', $article['author'], PDO::PARAM_STR);
        $statement->bindValue(':date', $article['date'], PDO::PARAM_STR);

        $statement->execute();
    }

    public function selectAllArticles(): array
    {
        $query = 'SELECT a.id, a.title, a.extract, a.author, a.date, c.name
        FROM ' . static::TABLE . ' as a
            INNER JOIN ' . static::TABLE2 . ' as c on a.category_id=c.id ORDER BY date DESC;';

        return $this->pdo->query($query)->fetchAll();
    }


    /**
     * Get 3 rows from database by most recent date.
     */
    public function selectLastThreeArticles(): array
    {
        // prepared request
        $query = "SELECT * FROM " . static::TABLE . " WHERE date <= CURDATE() ORDER BY date DESC LIMIT 3;";
        $statement = $this->pdo->query($query);

        return $statement->fetchAll();
    }

    /**
     * Get the title and the id value of every article.
     */
    public function getAllTitles(): array
    {
        // prepared request
        $query = "SELECT id, title FROM " . static::TABLE . ";";
        $statement = $this->pdo->query($query);

        return $statement->fetchAll();
    }

    /**
     * Get the list of articles by category
     */

    public function selectArticlesByCategory(int $id): array|false
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " as a 
        INNER JOIN " . static::TABLE2 . " as c ON a.category_id=c.id WHERE date <= CURDATE() AND c.id=:id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Delete article and pictures from an ID
     */
    public function deleteFullArticle(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE article , picture FROM " . static::TABLE . " LEFT 
        JOIN " . static::TABLE3 . " on picture.article_id = article.id WHERE article.id=:id;");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function updateArticle(array $article): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " 
        SET 
            `title` = :title,
            `extract` = :extract,
            `content` = :content,
            `photo` = :photo,
            `category_id` = :category_id,
            `author` = :author,
            `date` = :date
        WHERE 
            id=:id");
        $statement->bindValue(':title', $article['title'], PDO::PARAM_STR);
        $statement->bindValue(':extract', $article['extract'], PDO::PARAM_STR);
        $statement->bindValue(':content', $article['content'], PDO::PARAM_STR);
        $statement->bindValue(':photo', $article['photo'], PDO::PARAM_STR);
        $statement->bindValue(':category_id', $article['category_id'], PDO::PARAM_STR);
        $statement->bindValue(':author', $article['author'], PDO::PARAM_STR);
        $statement->bindValue(':date', $article['date'], PDO::PARAM_STR);
        $statement->bindValue(':id', $article['id'], PDO::PARAM_STR);

        return $statement->execute();
    }
}
