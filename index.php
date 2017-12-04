<?php
namespace Depouillement;

if (!is_dir('vendor')) {
    die('Vous devez executer "composer install".');
}
$loader = require __DIR__ . '/vendor/autoload.php';


include("./cfg/cfg.php");
try {
    $database = new Database($db_server, $db_base, $db_user, $db_password);
} catch (\PDOException $e) {
    die("Impossible de se connecter à la base de donnée : " . $e->getMessage());
}

$view = new Views\Index($database);

if (isset($_GET['userid'])
    and isset($_GET['month'])
    and isset($_GET['year'])
) {
    $view = new Views\Report($database, $_GET['userid'], $_GET['month'], $_GET['year']);
}

$view->show();
