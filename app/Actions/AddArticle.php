<?php
namespace Depouillement\Actions;

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

        $this->database->addArticle(
            $params['article_title'],
            $params['article_author_name'],
            $params['article_author_firstname'],
            $params['magazine_title'],
            $params['magazine_num'],
            $magazineDate,
            $params['magazine_page_start'],
            $params['magazine_page_end'],
            $params['commentary'],
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
