<?php
namespace Depouillement;

use \DateTime;

class Magazine
{
    public $title;
    public $issue;
    public $releaseDate;

    public function __construct(string $title, string $issue, DateTime $releaseDate)
    {
        $this->title = $title;
        $this->issue = $issue;
        $this->releaseDate = $releaseDate;
    }
}
