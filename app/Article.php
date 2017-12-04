<?php
namespace Depouillement;

use \DateTime;

class Article
{
    public $title;
    public $author;
    public $magazine;
    public $pageStart;
    public $pageEnd;
    public $comment;
    public $addingDate;

    public function __construct(
        string $title,
        Author $author,
        Magazine $magazine,
        int $pageStart,
        int $pageEnd,
        string $comment,
        DateTime $addingDate
    ) {
        $this->title = $title;
        $this->author = $author;
        $this->magazine = $magazine;
        $this->pageStart = $pageStart;
        $this->pageEnd = $pageEnd;
        $this->comment = $comment;
        $this->addingDate = $addingDate;
    }
}
