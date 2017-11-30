<?php
namespace Depouillement;

$userid = $_GET['id_user'];
$month = $_GET['month'];
$year = $_GET['year'];

$nom = $database->getUserNameByID($userid);
$listArticles = $database->getArticleByUserAndMonth($userid, $month, $year);


echo "<h1 class=\"Report\">Sélection d'articles pour ".$nom."</h1>";

foreach ($listArticles as $article) {
    $date = strtotime($article['date_magazine']);
    echo "<h3 class=\"report\">".$article['title']."</h3>";
    $str_date = date("d/m/Y", $date);
    echo "<p class=\"report\">Dans \""
        . $article['magazine']."\" n°"
        . $article['num_magazine']." (".$str_date.") à la page "
        . $article['page_magazine_start'].". "
        . $article['commentary']."<br />";
    //Affichage norme biblio

    echo "<b>Biblio : </b>";
    if (strcmp($article['author_name'], "") != 0) {
        echo $article['author_name'].", ".$article['author_firstname'].". ";
    }
    echo $article['title'].". <span style=\"font-style: italic;\">".$article['magazine']."</span>, ";
    $str_date = date("m/Y", $date);
    echo $str_date.", n°";
    echo $article['num_magazine'].", p. ".$article['page_magazine_start'];
    if ($article['page_magazine_end'] != null) {
        echo "-".$article['page_magazine_end']."</p>";
    }
}
?>


<!--La norme bibliographique pour les revues.

Auteur. Titre de l'article. Titre du périodique, date, n°, pagination

Exemple :
Resche, Christian. Ma vie avec un collègue psychopathe dans le même bureau. Psychopathe magazine, 09/2008, n° 13, p. 8

Il faut prévoir la ponctuation entre les champs (points ou virgule) ainsi que la
typographie (italique pour le titre de la revue) et les mentions n° et p. avant
le numéro et la page. Pour ce qui est de la virgule entre le nom et le prénom,
ce sera à Corinne de le mettre.
Si on veut être encore plus mieux, ce serait bien d'avoir la page de début et
de fin (donc un champ en plus) et dans la norme c'est noté p. 6-10.-->
