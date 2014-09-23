<?php //ON SUPPRIME L'UTILISATEUR SI IL EST DEFINIS
	if(isset($_POST['user_id'])) {	
		$db_query = "DELETE FROM `users` WHERE `users`.`id` = ".$_POST['user_id']." LIMIT 1";
		$db_result = mysql_query($db_query) 
			or die("Échec de la suppression d'utilisateur : " . mysql_error());
			
		//Et on enchaine sur tout les enregistrement le concernant.
		$db_query2 = "DELETE FROM `interrested` WHERE `interrested`.`id_user` = '".$_POST['user_id']."';";
		$db_result2 = mysql_query($db_query2) 
			or die("Échec de suppression des enregistrements de l'utilisateur : " . mysql_error());	
		warning("Utilisateur supprimé avec succès.");
	}
	
?>
<div class="page"> <!--CONTENU DE LA PAGE -->
<!--AFFICHAGE DE L'INTERFACE--> 				
<h1>SUPPRIMER UN UTILISATEUR</h1>
<p class="avertissement">
	Attention cette opération est irreversible ! l'utilisateur et toute
	les données le concernant dans le dépouillement seront supprimées.
</p>
<?php //INTERFACE DE SELECTION DE L'UTILISATEUR
	$db_query="SELECT * FROM users";
	$db_result = mysql_query($db_query) 
		or die('Échec de la requête : ' . mysql_error());
?>
<form method=post action="./index.php?action=DelUser">
	<select name="user_id" size="1">
		<?php 	
			while ($line = mysql_fetch_array($db_result, MYSQL_ASSOC))
				echo "<option value=\"".$line["id"]."\">".$line["name"]."</option>";
		?>
	</select>  
	<input type="submit" value="Supprimer" />
</form>

</div>
