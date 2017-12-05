<?php
namespace Depouillement\Actions;

use Depouillement\Article;
use Depouillement\Magazine;
use Depouillement\Author;
use \DateTime;

class ModArticle extends InterfaceAction
{
    protected $neededParameters = array(
        'articleid',
        'article_title',
        'author_name',
        'author_firstname',
        'magazine_title',
        'magazine_issue',
        'pageStart',
        'pageEnd',
        'magazineDay',
        'magazineMonth',
        'magazineYear',
        'comment',
    );

    protected function execute(array $params)
    {
        $magazineDate = $this->post['magazineYear']
            . "-" . $this->post['magazineMonth']
            . "-" . $this->post['magazineDay'];

        // Si les champs ne sont pas renseigné page_start et page_end peuvent être
        // nulles
        $pageStart = $params['pageStart'];
        $pageEnd = $params['pageEnd'];
        if (empty($pageStart)) {
            $pageStart = 0;
        }
        if (empty($pageEnd)) {
            $pageEnd = $pageStart;
        }
        $article = new Article(
            $params['article_title'],
            new Author(
                $params['author_name'],
                $params['author_firstname']
            ),
            new Magazine(
                $params['magazine_title'],
                $params['magazine_issue'],
                new DateTime($magazineDate)
            ),
            $pageStart,
            $pageEnd,
            $params['comment'],
            new DateTime('now')
        );

        $this->database->updateArticle(
            $params['articleid'],
            $article,
            $this->getInterrestedUserId()
        );
    }

    private function getInterrestedUserId()
    {
        $interrestedUsersId = array();
        foreach ($this->post as $key => $value) {
            if ($value === "on" and is_int($key)) {
                $interrestedUsersId[] = $key;
            }
        }
        return $interrestedUsersId;
    }
}
