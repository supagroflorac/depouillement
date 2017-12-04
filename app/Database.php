<?php
namespace Depouillement;

use \PDO;

class Database
{
    private $link = null;

    public function __construct(string $server, string $dbname, string $user, string $pwd)
    {
        $this->link = new PDO("mysql:host=$server;dbname=$dbname", $user, $pwd);
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM `users` ORDER BY `name`";
        return $this->query($query, array())
                    ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByID(int $userid)
    {
        $query = "SELECT * FROM `users` WHERE `id` = :userid";
        return $this->query($query, array(':userid' => $userid))
                    ->fetch(PDO::FETCH_ASSOC);
    }

    public function getGroupByID(int $groupid)
    {
        $query = "SELECT * FROM `groups` WHERE `id` = :groupid LIMIT 1";
        return $this->query($query, array(':groupid' => $groupid))
                    ->fetch(PDO::FETCH_ASSOC);
    }

    public function getArticlesByUserAndMonth(string $userid, int $month, int $year)
    {
        $query = "SELECT * FROM `articles` , `interrested`
                        WHERE `articles`.`id` = `interrested`.`id_article`
                        AND  `interrested`.`id_user` = :userid
                        AND `articles`.`add_date` BETWEEN :month
                            AND DATE_ADD(DATE_ADD(:month, INTERVAL +1 MONTH),
                                INTERVAL -1 DAY);";
        $sth = $this->query(
            $query,
            array(
                ':userid' => $userid,
                ':month' => "$year-$month-01",
            )
        );
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticlesByMonth(int $month, int $year)
    {
        $query = "SELECT * FROM `articles`
                        WHERE `articles`.`add_date` BETWEEN :month
                            AND DATE_ADD(DATE_ADD(:month, INTERVAL +1 MONTH), INTERVAL -1 DAY);";
        $sth = $this->query($query, array(':month' => "$year-$month-01"));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllGroups()
    {
        $query = "SELECT * FROM `groups` ORDER BY `name`";
        $sth = $this->query($query);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsersFromGroup(int $groupid)
    {
        $query = "SELECT * FROM `users` WHERE `id_group` = :groupid ORDER BY `name`;";
        $sth = $this->query($query, array(':groupid' => $groupid));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser(int $userid)
    {
        $query = "DELETE FROM `users`
                    WHERE `users`.`id` = :userid LIMIT 1;
                  DELETE FROM `interrested`
                    WHERE `interrested`.`id_user` = :userid;";
        $this->query($query, array(':userid' => $userid));
    }

    public function deleteGroup(int $groupid)
    {
        $query = "DELETE FROM `groups`
                    WHERE `groups`.`id` = :groupid LIMIT 1;
                  UPDATE `depouillement`.`users`
                    SET `id_group` = '0'
                    WHERE `users`.`id_group` = :groupid;";
        $this->query($query, array(':groupid' => $groupid));
    }

    public function deleteArticle(int $articleid)
    {
        $query = "DELETE FROM `articles`
                    WHERE `articles`.`id` = :articleid LIMIT 1;
                  DELETE FROM `interrested`
                    WHERE `interrested`.`id_article` = :articleid;";
        $this->query($query, array(':articleid' => $articleid));
    }

    public function addUser(string $name, int $groupid)
    {
        $query = "INSERT INTO `users` (`name`, `id_group`)
                    VALUES (:name , :groupid);";
        $this->query(
            $query,
            array(
                ':name' => $name,
                ':groupid' => $groupid
            )
        );
    }

    public function addGroup(string $name)
    {
        $query = "INSERT INTO `groups` (`name`)
                    VALUES (:name)";
        $this->query($query, array(':name' => $name));
    }

    public function addArticleUserLink($userid, $articleid)
    {
        $query = "INSERT INTO `interrested` (`id_user` , `id_article` )
                    VALUES (:userid, :articleid);";
        $this->query(
            $query,
            array(
                ':userid' => $userid,
                ':articleid' => $articleid,
            )
        );
    }

    public function getAllUsersId()
    {
        $query = "SELECT `id` FROM users";
        $results = $this->query($query)
                        ->fetchAll(PDO::FETCH_ASSOC);
        $listUsersId = array();
        foreach ($results as $line) {
            $listUsersId[] = $line['id'];
        }
        return $listUsersId;
    }

    public function addArticle(
        string $title,
        string $authorName,
        string $authorFirstname,
        string $magazine,
        string $numMagazine,
        string $dateMagazine,
        string $pageMagazineStart,
        string $pageMagineEnd,
        string $commentary,
        array $interrestedUsersId
    ) {
        $curDate = date("Y-m-d");
        $query = "INSERT INTO `articles` (
                `title`,
                `author_name`,
                `author_firstname`,
                `magazine`,
                `num_magazine`,
                `date_magazine`,
                `page_magazine_start`,
                `page_magazine_end`,
                `commentary`,
                `add_date`
            ) VALUES (
                :title,
                :authorName,
                :authorFirstname,
                :magazine,
                :numMagazine,
                :dateMagazine,
                :pageMagazineStart,
                :pageMagazineEnd,
                :commentary,
                :curDate
            );";
        $this->query(
            $query,
            array(
                ':title' => $title,
                ':authorName' => $authorName,
                ':authorFirstname' => $authorFirstname,
                ':magazine' => $magazine,
                ':numMagazine' => $numMagazine,
                ':dateMagazine' => $dateMagazine,
                ':pageMagazineStart' => $pageMagazineStart,
                ':pageMagazineEnd' => $pageMagineEnd,
                ':commentary' => $commentary,
                ':curDate' => $curDate,
            )
        );

        $articleid = $this->link->lastInsertId();
        $validUsersId = $this->getAllUsersId();

        foreach ($interrestedUsersId as $userid) {
            if (in_array($userid, $validUsersId)) {
                $this->addArticleUserLink($userid, $articleid);
            }
        }
    }

    public function updateGroup(int $groupid, string $groupName)
    {
        $query = "UPDATE `groups` SET name = :name WHERE id = :id";
        $this->query($query, array(':name' => $groupName, ':id' => $groupid));
    }

    public function updateUser(int $userid, string $username, int $groupid)
    {
        $query = "UPDATE `users`
                    SET name = :name, id_group = :groupid
                    WHERE id = :userid";
        $this->query(
            $query,
            array(
                ':name' => $username,
                ':userid' => $userid,
                ':groupid' => $groupid,
            )
        );
    }

    private function query(string $query, array $params = array())
    {
        $sth = $this->link->prepare($query);
        if ($sth->execute($params) === false) {
            throw new DatabaseException($query, $sth->errorInfo());
        }
        return $sth;
    }
}
