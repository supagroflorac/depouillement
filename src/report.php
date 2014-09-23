<?php
//On recupère le nom de l'utilisateur
$db_query = "SELECT `name` FROM `users` WHERE `users`.`id` = ".$_GET['id_user'].";";
$db_result = mysql_query($db_query);
$ll = mysql_fetch_array($db_result, MYSQL_ASSOC);
$nom = $ll['name'];

echo "<h1 class=\"Report\">Sélection d'articles pour ".$nom."</h1>";

//Calcul de la date.
$date = $_GET['year']."-".$_GET['month']."-01";
//$date = strtotime($date_deb);
//date_add($date, "P1M"); //Ajout d'un mois.
//$date_fin = date("Y-m-d", $date);


//ancienn version sans prise en compte des mois
//$db_query = "SELECT * FROM `article` , `interrested` WHERE `article`.`id` = `interrested`.`id_article` AND  `interrested`.`id_user` = '".$_GET['id_user']."';";
$db_query = "SELECT * FROM `articles` , `interrested` 
				WHERE `articles`.`id` = `interrested`.`id_article` 
				AND  `interrested`.`id_user` = '".$_GET['id_user']."'
				AND `articles`.`add_date` BETWEEN '".$date."' AND DATE_ADD(DATE_ADD('".$date."', INTERVAL +1 MONTH), INTERVAL -1 DAY);";

$db_result66 = mysql_query($db_query) 
	or die("Échec de lecture de liste d'article : " . mysql_error());
	
while ($data = mysql_fetch_array($db_result66, MYSQL_ASSOC)) {
	$date = strtotime($data['date_magazine']);
	echo "<h3 class=\"report\">".$data['title']."</h3>";
	$str_date = date("d/m/Y", $date);
	echo "<p class=\"report\">Dans \"".$data['magazine']."\" n°".$data['num_magazine']." (".$str_date.") à la page ".$data['page_magazine_start'].". ".$data['commentary']."<br />";
	//Affichage norme biblio
	
	echo "<b>Biblio : </b>";
	if (strcmp($data['author_name'], "") != 0) {
		echo $data['author_name'].", ".$data['author_firstname'].". ";
	}
	echo $data['title'].". <span style=\"font-style: italic;\">".$data['magazine']."</span>, ";
	$str_date = date("m/Y", $date);
	echo $str_date.", n°";
	echo $data['num_magazine'].", p. ".$data['page_magazine_start'];
	if ($data['page_magazine_end']!=NULL)
		echo "-".$data['page_magazine_end']."</p>";
} 
?>


<!--La norme bibliographique pour les revues.

Auteur. Titre de l'article. Titre du périodique, date, n°, pagination

Exemple :
Resche, Christian. Ma vie avec un collègue psychopathe dans le même bureau. Psychopathe magazine, 09/2008, n° 13, p. 8

Il faut prévoir la ponctuation entre les champs (points ou virgule) ainsi que la typographie (italique pour le titre de la revue) et les mentions n° et p. avant le numéro et la page. Pour ce qui est de la virgule entre le nom et le prénom, ce sera à Corinne de le mettre.
Si on veut être encore plus mieux, ce serait bien d'avoir la page de début et de fin (donc un champ en plus) et dans la norme c'est noté p. 6-10.-->
