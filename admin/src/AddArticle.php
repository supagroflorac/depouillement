<?php
	if(isset($_POST['article_title'], $_POST['magazine_title'])) {
		//AJOUT DE L'ARTICLE A LA BASE
		$mag_date = $_POST['mag_date_year']."-".$_POST['mag_date_month']."-".$_POST['mag_date_day'];
		//echo $mag_date."<br />";
		
		$db_query = "INSERT INTO `articles` 
		                    (`id` ,   `title` ,                       `author_name` ,                       `author_firstname` ,                       `magazine` ,                    `num_magazine` ,             `date_magazine` ,  `page_magazine_start` ,               `page_magazine_end` ,               `commentary`, 			`add_date` ) 
					 VALUES (NULL , '".$_POST['article_title']."', '".$_POST['article_author_name']."' , '".$_POST['article_author_firstname']."' , '".$_POST['magazine_title']."' , '".$_POST['magazine_num']."' , '".$mag_date."' , '".$_POST['magazine_page_start']."' , '".$_POST['magazine_page_end']."' , '".$_POST['commentary']."' , '".date("Y-m-d")."');";
		$db_result1 = mysql_query($db_query) 
			or die("Échec de l'ajout d'article : " . mysql_error());
			
		//RECUPERATION DE L'ID DE L'ARTICLE	
		$db_query = " SELECT `id` FROM `articles` WHERE `title` = '".$_POST['article_title']."';";
		$db_result2 = mysql_query($db_query) 
			or die("Article introuvable : " . mysql_error());
		
		$data = mysql_fetch_array($db_result2, MYSQL_ASSOC);
		$id_article = $data['id'];
		
		//AJOUTER LE LIEN ENTRE UTILISATEUR ET ARTICLE
		$db_query="SELECT `id` FROM users";
		$db_result3 = mysql_query($db_query) 
			or die('Échec de la requête : ' . mysql_error());
		while ($line = mysql_fetch_array($db_result3, MYSQL_ASSOC)) {
			$id_user = $line['id'];
			if(isset($_POST[$id_user]))
			{
				$db_query="INSERT INTO `interrested` (`id` , `id_user` , `id_article` )
							VALUES (NULL , '".$id_user."', '".$id_article."');";
				$db_result4 = mysql_query($db_query) 
					or die("Échec de l'ajout d'article : " . mysql_error());
			}	
		}
		warning("Article ajouté avec succès.");		
	}

?>

<div class="page"> <!--CONTENU DE LA PAGE -->
<!--AFFICHAGE DE L'INTERFACE--> 				
<h1>AJOUTER UN ARTICLE</h1>
<form method="post" action="./index.php?action=AddArticle">
	<fieldset>
		<legend>Article</legend>
		<label for="article_title">Titre : </label><input id="article_title" type="text" name="article_title" maxlength="128" size="64" /> <br />
		<fieldset>
			<legend>Auteur : </legend>
			<label for="article_author_name">Nom : </label><input id="article_author_name" type="text" name="article_author_name" maxlength="64" size="32" /> 
			<label for="article_author_firstname">Prénom : </label><input id="article_author_firstname" type="text" name="article_author_firstname" maxlength="64" size="32" /><br />
		</fieldset>
	</fieldset>
	<fieldset>
		<legend>Magazine</legend>
		Titre du magazine : <input type="text" name="magazine_title" maxlength="128" size="64" /> <br />
		Num. du magazine : <input type="text" name="magazine_num" maxlength="16" size="16" />
		Page : <input type="text" name="magazine_page_start" maxlength="5" size="4" />-<input type="text" name="magazine_page_end" maxlength="5" size="4" /> <br />
		Date du magazine : <input type="text" name="mag_date_day" maxlength="10" size="5" />/
						   <input type="text" name="mag_date_month" maxlength="10" size="5" />/
						   <input type="text" name="mag_date_year" maxlength="4" size="4" /><br />
	</fieldset>		
	<fieldset>
		<legend>Commentaire</legend>
		<textarea name="commentary" cols="250" rows="4"></textarea><br />
	</fieldset>
<?php //Affichage des utilisateurs :
		$db_query_groups="SELECT * FROM groups ORDER BY `name`;";
		$db_result_groups = mysql_query($db_query_groups) 
			or die('Échec de la requête : ' . mysql_error());
		while ($group = mysql_fetch_array($db_result_groups, MYSQL_ASSOC)){
			echo "\t<fieldset id=\"".$group["id"]."\">\n";
			echo "\t\t\t<legend>".$group["name"]." (<span class=\"action\" onclick=\"checkAll('".$group["id"]."')\">tous</span>/<span class=\"action\" onclick=\"checkNone('".$group["id"]."')\">aucun</span>)</legend>";
			$db_query_users="SELECT * FROM `users` WHERE `id_group` = '".$group["id"]."' ORDER BY `name`;";
			$db_result_users = mysql_query($db_query_users) 
				or die('Échec de la requête : ' . mysql_error());
			while ($user = mysql_fetch_array($db_result_users, MYSQL_ASSOC)){
				echo "\t\t\t<input class=\"list_group\" type=\"checkbox\" name=\"".$user["id"]."\" id=\"".$user["id"]."\" /><label for=\"".$user["id"]."\">".$user["name"]."</label>\n";
			}
			echo "\t</fieldset>\n";
			mysql_free_result($db_result_users);
		}
		// LES SANS-GROUPE
		echo "<fieldset>\n";
			$db_query_users="SELECT * FROM `users` WHERE `id_group` = '0' ORDER BY `name`;";
			echo "<legend>sans groupe (<a href=\"#\" onclick=\"tous('name')\">tous</a>/<a href=\"#\" onclick=\"tous('name')\">aucun</a>)</legend>";
			$db_result_users = mysql_query($db_query_users) 
				or die('Échec de la requête : ' . mysql_error());
			while ($user = mysql_fetch_array($db_result_users, MYSQL_ASSOC)){
				echo "<span class=\"list_group\"><input id=\"".$user["id"]."\" class=\"list_group\" type=\"checkbox\" name=\"".$user["id"]."\" /><label for=\"".$user["id"]."\">".$user["name"]."</label></span>\n";
			}
			echo "</fieldset>\n";
	?>
	
	<input type="submit" value="Créer" />
</form>
</div>
