<?php
namespace Depouillement\Views;

class ListArticles extends InterfaceView
{

    public function __construct($database, $month, $year)
    {
        parent::__construct($database);
        $this->month = $month;
        $this->year = $year;
    }

    protected function compileInfos()
    {
        $data = array(
            'articles' => $this->database->getArticlesByMonth($this->month, $this->year),
            'year' => $this->year,
            'month' => $this->month,
            'months' => array(
                '01' => 'Janvier',
                '02' => 'Février',
                '03' => 'Mars',
                '04' => 'Avril',
                '05' => 'Mai',
                '06' => 'Juin',
                '07' => 'Juillet',
                '08' => 'Août',
                '09' => 'Septembre',
                '10' => 'Octobre',
                '11' => 'Novembre',
                '12' => 'Décembre',
            ),
        );
        return $data;
    }
}
