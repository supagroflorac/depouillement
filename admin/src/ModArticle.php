
<!-- Modification de la base de données !-->
<?php 
if(isset($_POST['id_article'], $_POST['save_article'])) {
		//AJOUT DE L'ARTICLE A LA BASE
		$mag_date = $_POST['mag_date_year']."-".$_POST['mag_date_month']."-".$_POST['mag_date_day'];
		//echo $mag_date."<br />";
		
		$db_query_update = "UPDATE `articles` SET  `title` =  '".$_POST['article_title']."' ,  
											`author_name` = '".$_POST['article_author_name']."' ,
											`author_firstname` = '".$_POST['article_author_firstname']."' , 
											`magazine` = '".$_POST['magazine_title']."' ,
											`num_magazine` = '".$_POST['magazine_num']."' ,
											`date_magazine` = '".$mag_date."' ,  
											`page_magazine_start` = '".$_POST['magazine_page_start']."' ,
											`page_magazine_end` = '".$_POST['magazine_page_end']."' ,
											`commentary` = '".$_POST['commentary']."' ,
											`add_date` = '".date("Y-m-d")."'
										WHERE `articles`.`id` = '".$_POST['id_article']."';";
		$db_result_update = mysql_query($db_query_update) 
			or die("Échec de l'ajout d'article : " . mysql_error());
			
		
		//SUPPRIMER TOUS LES LIENS CONCERNANT CET ARTICLE AVANT DE LES REFAIRE
		$db_query_del_links = "DELETE FROM `interrested` WHERE `interrested`.`id_article` = '".$_POST['id_article']."';";
		$db_result_del_links = mysql_query($db_query_del_links) 
			or die('Échec de la requête : ' . mysql_error());
		
		//ON RECREE LES NOUVEAU LIEN ENTRE UTILISATEUR ET ARTICLE
		$db_query="SELECT `id` FROM users";
		$db_result3 = mysql_query($db_query) 
			or die('Échec de la requête : ' . mysql_error());
		while ($line = mysql_fetch_array($db_result3, MYSQL_ASSOC)) {
			$id_user = $line['id'];
			if(isset($_POST[$id_user]))
			{
				$db_query="INSERT INTO `interrested` (`id` , `id_user` , `id_article` )
							VALUES (NULL , '".$id_user."', '".$_POST['id_article']."');";
				$db_result4 = mysql_query($db_query) 
					or die("Échec de l'ajout d'article : " . mysql_error());
			}	
		}
		warning("Article modifié avec succès.");		
	}
?>

<div class="page"> <!--CONTENU DE LA PAGE -->
<!--AFFICHAGE DE L'INTERFACE--> 				
<h1>MODFIER UN ARTICLE</h1>

<h1 style="color:red;">NE PAS UTILISER EN COUR DE DEVELOPPEMENT</h1>



<!-- Choix de l'article -->
<?php if (!isset($_POST["id_article"])) { ?>
	<?php //INTERFACE DE SELECTION DE L'UTILISATEUR
		
		if (isset($_POST["month"]) && isset($_POST["year"])) {
			$date = $_POST['year']."-".$_POST['month']."-01";
			$db_query="SELECT * FROM articles WHERE `articles`.`date_magazine` BETWEEN '".$date."' AND DATE_ADD('".$date."', INTERVAL +1 MONTH) ORDER BY date_magazine";
		} else {
			$db_query="SELECT * FROM articles ORDER BY date_magazine";
		}
		$db_result = mysql_query($db_query) 
			or die('Échec de la requête : ' . mysql_error());
	?>

	<!-- choix par date -->
	
	<table>
		<tr>
			<td>Selection de l'intervale de temps : </td>	
			<td><form method="post" action="./index.php?action=ModArticle">
				<select name="month" size="1">
					<option value="01" <?php if (date("m") == "01") echo "selected=\"selected\"" ?>>Janvier</option>
					<option value="02" <?php if (date("m") == "02") echo "selected=\"selected\"" ?>>Février</option>
					<option value="03" <?php if (date("m") == "03") echo "selected=\"selected\"" ?>>Mars</option>
					<option value="04" <?php if (date("m") == "04") echo "selected=\"selected\"" ?>>Avril</option>
					<option value="05" <?php if (date("m") == "05") echo "selected=\"selected\"" ?>>Mai</option>
					<option value="06" <?php if (date("m") == "06") echo "selected=\"selected\"" ?>>juin</option>
					<option value="07" <?php if (date("m") == "07") echo "selected=\"selected\"" ?>>Juillet</option>
					<option value="08" <?php if (date("m") == "08") echo "selected=\"selected\"" ?>>Août</option>
					<option value="09" <?php if (date("m") == "09") echo "selected=\"selected\"" ?>>Septembre</option>
					<option value="10" <?php if (date("m") == "10") echo "selected=\"selected\"" ?>>Octobre</option>
					<option value="11" <?php if (date("m") == "11") echo "selected=\"selected\"" ?>>Novembre</option>
					<option value="12" <?php if (date("m") == "12") echo "selected=\"selected\"" ?>>Décembre</option>			
				</select>
				<input type="test" name="year" value="<?php echo date("Y")?>" size="4" maxlength="4"> 
				<input type="submit" value="Sélectionner" />
			</form></td>
			<td><form method="post" action="./index.php?action=ModArticle">
				<input type="submit" value="Annuler Selection" />
			</form></td>
		</tr>
	</table>


	<form method="post" action="./index.php?action=ModArticle">
		<label>Selection de l'article : </label>
		<select class="choose_article2" name="id_article" size="1">
			<?php 	
				while ($line = mysql_fetch_array($db_result, MYSQL_ASSOC))
					echo "<option value=\"".$line["id"]."\">".$line["title"].", ".$line["magazine"]." (".$line["date_magazine"].")</option>";
			?>
		</select>
		<input type="submit" value="Modifier" />
	</form>
<?php } ?>


<!-- EDITION DE L'ARTICLE SI SON ID EST DEFINIE -->
<?php if (isset($_POST["id_article"])) {

$db_query_article = "SELECT * FROM `articles` WHERE `id` = '".$_POST["id_article"]."' LIMIT 1;";
$db_result_article = mysql_query($db_query_article) 
	or die('Échec de la requête : ' . mysql_error());
$article = mysql_fetch_array($db_result_article, MYSQL_ASSOC);
	 ?>
	<form method="post" action="./index.php?action=AddArticle">
		<input type= "hidden" name= "id_article" value="<?php echo $_POST["id_article"]; ?>">
		<input type= "hidden" name= "save_article" value="oui">
		<fieldset>
			<legend>Article</legend>
			<label for="article_title">Titre : </label><input id="article_title" type="text" name="article_title" maxlength="128" size="64" value="<?php echo $article["title"]; ?>" /> <br />
			<fieldset>
				<legend>Auteur : </legend>
				<label for="article_author_name">Nom : </label><input id="article_author_name" type="text" name="article_author_name" maxlength="64" size="32" value="<?php echo $article["author_name"]; ?>" /> 
				<label for="article_author_firstname">Prénom : </label><input id="article_author_firstname" type="text" name="article_author_firstname" maxlength="64" size="32" value="<?php echo $article["author_firstname"]; ?>" /><br />
			</fieldset>
		</fieldset>
		<fieldset>
			<legend>Magazine</legend>
			Titre du magazine : <input type="text" name="magazine_title" maxlength="128" size="64" value="<?php echo $article["magazine"]; ?>" /> <br />
			Num. du magazine : <input type="text" name="magazine_num" maxlength="16" size="16" value="<?php echo $article["num_magazine"]; ?>" />
			Page : <input type="text" name="magazine_page_start" maxlength="5" size="4" value="<?php echo $article["page_magazine_start"]; ?>"/>-<input type="text" name="magazine_page_end" maxlength="5" size="4" value="<?php echo $article["page_magazine_end"]; ?>" /> <br />
			Date du magazine : <input type="text" name="mag_date_day" maxlength="10" size="5" value="<?php echo date("d", strtotime($article["date_magazine"])); ?>" />/
							   <input type="text" name="mag_date_month" maxlength="10" size="5" value="<?php echo date("m", strtotime($article["date_magazine"])); ?>" />/
							   <input type="text" name="mag_date_year" maxlength="4" size="4" value="<?php echo date("y", strtotime($article["date_magazine"])); ?>"/><br />
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
					//On verifier si il faut cocher ou pas la case
					$db_query_inter="SELECT * FROM `interrested` WHERE `id_user` = '".$user["id"]."' AND `id_article` = '".$_POST["id_article"]."';";
					$db_result_inter = mysql_query($db_query_inter)or die('Prout : ' . mysql_error());
					
					echo "\t\t\t<input class=\"list_group\" type=\"checkbox\" name=\"".$user["id"]."\" id=\"".$user["id"]."\" ";
					if (mysql_num_rows($db_result_inter) != 0)
						echo "checked=\"checked\"";
					/*else
						echo "value=\"0\"";*/
					echo "/><label for=\"".$user["id"]."\">".$user["name"]."</label>\n";
				}
				echo "\t</fieldset>\n";
				mysql_free_result($db_result_users);
			}
			// LES SANS-GROUPE
			echo "<fieldset>\n";
				$db_query_users="SELECT * FROM `users` WHERE `id_group` = '0' ORDER BY `name`;";
				echo "<legend>sans groupe ((<span class=\"action\" onclick=\"checkAll('".$group["id"]."')\">tous</span>/<span class=\"action\" onclick=\"checkNone('0')\">aucun</span>))</legend>";
				$db_result_users = mysql_query($db_query_users) 
					or die('Échec de la requête : ' . mysql_error());
				while ($user = mysql_fetch_array($db_result_users, MYSQL_ASSOC)){
					
					$db_query_inter="SELECT * FROM `interrested` WHERE `id_user` = '".$user["id"]."' AND `id_article` = '".$_POST["id_article"]."';";
					$db_result_inter = mysql_query($db_query_inter)or die('Prout : ' . mysql_error());
					
					echo "<span class=\"list_group\"><input id=\"".$user["id"]."\" class=\"list_group\" type=\"checkbox\" name=\"".$user["id"]."\" /><label for=\"".$user["id"];
					if (mysql_num_rows($db_result_inter) != 0)
						echo "checked=\"checked\"";
					echo " />".$user["name"]."</label></span>\n";
				}
				echo "</fieldset>\n";
		?>
		<input type="submit" value="Modifier" />
	</form>
<?php } ?>

</div>


