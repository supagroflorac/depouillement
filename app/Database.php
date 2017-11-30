<?php
namespace Depouillement;

use \PDO;

class Database
{
    private $link = null;

    public function connect($server, $dbname, $user, $pwd)
    {
        $this->link = new PDO("mysql:host=$server;dbname=$dbname", $user, $pwd);
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM `users` ORDER BY `name`";
        $sth = $this->link->prepare($query);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserNameByID(int $userid)
    {
        $query = "SELECT name FROM users WHERE id = :userid";
        $sth = $this->link->prepare($query);
        $sth->execute(array(':userid' => $userid));
        return $sth->fetch(PDO::FETCH_ASSOC)['name'];
    }

    public function getArticleByUserAndMonth(string $userid, int $month, int $year)
    {
        $query = "SELECT * FROM `articles` , `interrested`
                        WHERE `articles`.`id` = `interrested`.`id_article`
                        AND  `interrested`.`id_user` = :userid
                        AND `articles`.`add_date` BETWEEN :month
                            AND DATE_ADD(DATE_ADD(:month, INTERVAL +1 MONTH), INTERVAL -1 DAY);";
        $sth = $this->link->prepare($query);
        $sth->execute(
            array(
                ':userid' => $userid,
                ':month' => "$year-$month-01",
            )
        );
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
