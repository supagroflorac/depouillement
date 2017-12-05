<?php
namespace Depouillement;

if (!is_dir('../vendor')) {
    die('Vous devez executer "composer install".');
}
$loader = require __DIR__ . '/../vendor/autoload.php';

include("../cfg/cfg.php");
try {
    $database = new Database($db_server, $db_base, $db_user, $db_password);
} catch (\PDOException $e) {
    die("Impossible de se connecter à la base de donnée : " . $e->getMessage());
}
if (!isset($_GET['view'])) {
    $_GET['view'] = 'default';
}

if (isset($_GET['action'])) {
    $actionClassName = 'Depouillement\\Actions\\' . $_GET['action'];
    $action = new $actionClassName($database, $_GET, $_POST);
    $action->run();
}

switch ($_GET['view']) {
    case "ListUsers":
        $view = new Views\ListUsers($database);
        break;
    case "ListGroups":
        $view = new Views\ListGroups($database);
        break;
    case "AddArticle":
        $view = new Views\AddArticle($database);
        break;
    case "AddUser":
        $view = new Views\AddUser($database);
        break;
    case "AddGroup":
        $view = new Views\AddGroup($database);
        break;
    // TODO vérifier que les ID existent pour les modifications.
    case "ModGroup":
        if (!isset($_GET['groupid'])) {
            die('Pas de groupe a editer.');
        }
        $view = new Views\ModGroup($database, $_GET['groupid']);
        break;
    case "ModUser":
        if (!isset($_GET['userid'])) {
            die('Pas d\'utilisateur a editer.');
        }
        $view = new Views\ModUser($database, $_GET['userid']);
        break;
    case "ModArticle":
        if (!isset($_GET['articleid'])) {
            die('Pas d\'article a editer.');
        }
        $view = new Views\ModArticle($database, $_GET['articleid']);
        break;
    default:
        $month = date('m');
        $year = date('Y');
        if (isset($_GET['month'])) {
            $month = $_GET['month'];
        }
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
        }
        $view = new Views\ListArticles($database, $month, $year);
        break;
}

$view->show();
