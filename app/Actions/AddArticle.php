<?php
namespace Depouillement\Actions;

use \Depouillement\Article;
use \Depouillement\Author;
use \Depouillement\Magazine;
use \DateTime;

class AddArticle extends InterfaceAction
{
    protected $neededParameters = array(
        'article_title',
        'article_author_name',
        'article_author_firstname',
        'magazine_title',
        'magazine_num',
        'magazine_page_start',
        'magazine_page_end',
        'mag_date_day',
        'mag_date_month',
        'mag_date_year',
        'commentary',
    );

    protected function execute(array $params)
    {
        $magazineDate = $this->post['mag_date_year']
            . "-" . $this->post['mag_date_month']
            . "-" . $this->post['mag_date_day'];

        // Si les champs ne sont pas renseigné page_start et page_end peuvent être
        // nulles
        $pageStart = $params['magazine_page_start'];
        $pageEnd = $params['magazine_page_end'];
        if (empty($pageStart)) {
            $pageStart = 0;
        }
        if (empty($pageEnd)) {
            $pageEnd = $pageStart;
        }

        $this->database->addArticle(
            new Article(
                $params['article_title'],
                new Author(
                    $params['article_author_name'],
                    $params['article_author_firstname']
                ),
                new Magazine(
                    $params['magazine_title'],
                    $params['magazine_num'],
                    new DateTime($magazineDate)
                ),
                $params['magazine_page_start'],
                $pageEnd,
                $params['commentary'],
                new DateTime('now')
            ),
            $this->getInterrestedUserId() // listes des personnes intérressée.
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
