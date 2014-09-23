<?php //ON SUPPRIME L'ARTICLE SI IL EST DEFINIS
	if(isset($_POST['article_id'])) {	
		$db_query = "DELETE FROM `articles` WHERE `articles`.`id` = ".$_POST['article_id']." LIMIT 1";
		$db_result = mysql_query($db_query) 
			or die("Échec de la suppression de l'article : " . mysql_error());
			
		//Et on enchaine sur tout les enregistrement le concernant.
		$db_query2 = "DELETE FROM `interrested` WHERE `interrested`.`id_article` = '".$_POST['article_id']."';";
		$db_result2 = mysql_query($db_query2) 
			or die("Échec de suppression des références à l'article : " . mysql_error());	
		warning("Article supprimé avec succès.");
	}
	
?>
<div class="page"> <!--CONTENU DE LA PAGE -->
<!--AFFICHAGE DE L'INTERFACE--> 				
<h1>SUPPRIMER UN ARTICLE</h1>
<p class="avertissement">
	Attention cette opération est irreversible ! l'article et toute 
	les données le concernant dans le dépouillement seront supprimées.
</p>
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
		<td><form method="post" action="./index.php?action=DelArticle">
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
		<td><form method="post" action="./index.php?action=DelArticle">
			<input type="submit" value="Annuler Selection" />
		</form></td>
	</tr>
</table>


<form method="post" action="./index.php?action=DelArticle">
	<select class="choose_article" name="article_id" size="20">
		<?php 	
			while ($line = mysql_fetch_array($db_result, MYSQL_ASSOC))
				echo "<option value=\"".$line["id"]."\">".$line["title"].", ".$line["magazine"]." (".$line["date_magazine"].")</option>";
		?>
	</select><br />  
	<input type="submit" value="Supprimer" />
</form>
</div>
