<?php
namespace Depouillement\Views;

class Report extends InterfaceView
{
    private $userid;
    private $month;
    private $year;

    public function __construct($database, $userid, $month, $year)
    {
        parent::__construct($database);
        $this->userid = $userid;
        $this->month = $month;
        $this->year = $year;
    }

    protected function compileInfos()
    {
        $data = array(
            'username' => $this->database->getUserByID($this->userid)['name'],
            'articles' => $this->database->getArticlesByUserAndMonth(
                $this->userid,
                $this->month,
                $this->year
            ),
            'month' => $this->month,
            'year' => $this->year,
        );
        return $data;
    }
}
