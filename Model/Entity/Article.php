<?php

namespace App\Model\Entity;

class Article extends AbstractEntity
{
    private string $title;
    private string $content;
    private DateTime $dateAdd;
    private DateTime $dateUpdate;
    private User $author;


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return DateTime
     */
    public function getDateAdd(): DateTime
    {
        return $this->dateAdd;
    }

    /**
     * @param DateTime $dateAdd
     */
    public function setDateAdd(DateTime $dateAdd): void
    {
        $this->dateAdd = $dateAdd;
    }

    /**
     * @return DateTime
     */
    public function getDateUpdate(): DateTime
    {
        return $this->dateUpdate;
    }

    /**
     * @param DateTime $dateUpdate
     */
    public function setDateUpdate(DateTime $dateUpdate): void
    {
        $this->dateUpdate = $dateUpdate;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }
}