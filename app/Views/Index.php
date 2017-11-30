<?php
namespace Depouillement\Views;

class Index extends InterfaceView
{
    protected function compileInfos()
    {
        $data = array(
            'users' => $this->database->getAllUsers(),
            'curMonth' => date("m"),
            'curYear' => date('Y'),
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
                '10' => 'Ocotobre',
                '11' => 'Novembre',
                '12' => 'Décembre',
            ),
        );
        return $data;
    }
}
