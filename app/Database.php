<?php
namespace Depouillement;

use \PDO;
use \DateTime;

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

    public function getAllGroups()
    {
        $query = "SELECT * FROM `groups` ORDER BY `name`";
        $sth = $this->query($query);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
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

    public function getArticleByID(int $articleid)
    {
        $query = "SELECT * FROM `articles` WHERE `id` = :articleid LIMIT 1";

        return $this->articleArrayToArticleClass(
            $this->query($query, array(':articleid' => $articleid))
                    ->fetch(PDO::FETCH_ASSOC)
        );
    }

    public function isInterrested($userid, $articleid)
    {
        $query = "SELECT * FROM `interrested`
                    WHERE `id_user` = :userid
                    AND `id_article` = :articleid
                    LIMIT 1";

        $results = $this->query(
            $query,
            array(
                ':articleid' => $articleid,
                ':userid' => $userid,
            )
        )->fetch(PDO::FETCH_ASSOC);

        if (empty($results)) {
            return false;
        }
        return true;
    }

    public function getArticlesByUserAndMonth(string $userid, int $month, int $year)
    {
        $query = "SELECT * FROM `articles` , `interrested`
                    WHERE `articles`.`id` = `interrested`.`id_article`
                        AND  `interrested`.`id_user` = :userid
                        AND `articles`.`add_date` BETWEEN :month
                            AND DATE_ADD(
                                DATE_ADD(:month, INTERVAL +1 MONTH), INTERVAL -1 DAY
                            );";
        $sth = $this->query(
            $query,
            array(
                ':userid' => $userid,
                ':month' => "$year-$month-01",
            )
        );
        return $this->articlesArrayToArticlesClass(
            $sth->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function getArticlesByMonth(int $month, int $year)
    {
        $query = "SELECT * FROM `articles`
                        WHERE `articles`.`add_date` BETWEEN :month
                            AND DATE_ADD(
                                    DATE_ADD(:month, INTERVAL +1 MONTH),
                                    INTERVAL -1 DAY
                                );";
        $sth = $this->query($query, array(':month' => "$year-$month-01"));
        return $this->articlesArrayToArticlesClass(
            $sth->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function getAllUsersFromGroup(int $groupid)
    {
        $query = "SELECT * FROM `users`
                    WHERE `id_group` = :groupid
                    ORDER BY `name`;";
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

    public function addArticle(Article $article, array $interrestedUsersId)
    {
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
                ':title' => $article->title,
                ':authorName' => $article->author->name,
                ':authorFirstname' => $article->author->firstname,
                ':magazine' => $article->magazine->title,
                ':numMagazine' => $article->magazine->issue,
                ':dateMagazine' => $article->magazine->releaseDate->format('Y-m-d'),
                ':pageMagazineStart' => $article->pageStart,
                ':pageMagazineEnd' => $article->pageEnd,
                ':commentary' => $article->comment,
                ':curDate' => $article->addingDate->format('Y-m-d'),
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

    public function updateArticle(int $articleid, Article $article, array $interrestedUsersId)
    {
        $this->query(
            "UPDATE `articles`
                SET  `title` =  :title,
                     `author_name` = :authorName,
                     `author_firstname` = :authorFirstname,
                     `magazine` = :magazineTitle,
                     `num_magazine` = :magazineIssue,
                     `date_magazine` = :magazineDateRelease,
                     `page_magazine_start` = :pageStart,
                     `page_magazine_end` = :pageEnd,
                     `commentary` = :comment
                WHERE `articles`.`id` = :articleid
                LIMIT 1;
            DELETE FROM `interrested`
                WHERE `interrested`.`id_article` = :articleid;",
            array(
                ':title' => $article->title,
                ':authorName' => $article->author->name,
                ':authorFirstname' => $article->author->firstname,
                ':magazineTitle' => $article->magazine->title,
                ':magazineIssue' => $article->magazine->issue,
                ':magazineDateRelease' => $article->magazine->releaseDate->format('Y-m-d'),
                ':pageStart' => $article->pageStart,
                ':pageEnd' => $article->pageEnd,
                ':comment' => $article->comment,
                ':articleid' => $articleid,
            )
        );

        $validUsersId = $this->getAllUsersId();
        foreach ($interrestedUsersId as $userid) {
            if (in_array($userid, $validUsersId)) {
                $this->addArticleUserLink($userid, $articleid);
            }
        }
    }

    private function query(string $query, array $params = array())
    {
        $sth = $this->link->prepare($query);
        if ($sth->execute($params) === false) {
            throw new DatabaseException($query, $sth->errorInfo());
        }
        return $sth;
    }

    private function articleArrayToArticleClass(array $data)
    {

        // Dans la BD les anciennes entrées peuvent avoir null a la place de
        // page_start ou page_end
        $pageStart = $data['page_magazine_start'];
        $pageEnd = $data['page_magazine_end'];
        if (empty($pageStart)) {
            $pageStart = 0;
        }
        if (empty($pageEnd)) {
            $pageEnd = $pageStart;
        }

        return new Article(
            $data['title'],
            new Author($data['author_name'], $data['author_firstname']),
            new Magazine(
                $data['magazine'],
                $data['num_magazine'],
                new DateTime($data['date_magazine'])
            ),
            $pageStart,
            $pageEnd,
            $data['commentary'],
            new DateTime($data['add_date'])
        );
    }

    private function articlesArrayToArticlesClass(array $data)
    {
        $articles = array();
        foreach ($data as $line) {
            $article = $this->articleArrayToArticleClass($line);
            $articles[$line['id']] = $article;
        }
        return $articles;
    }
}
