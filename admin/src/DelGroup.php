<?php //ON SUPPRIME LE GROUPE SI IL EST DEFINIS
	if(isset($_POST['group_id'])) {	
		$db_query_delete = "DELETE FROM `groups` WHERE `groups`.`id` = ".$_POST['group_id']." LIMIT 1;";
		$db_result_delete = mysql_query($db_query_delete) 
			or die("Échec de la suppression du groupe : " . mysql_error());
		
		//Tout les utilisateurs associé a ce groupe sont affecté au "--sans groupe--"	
		$db_query_update = "UPDATE `depouillement`.`users` SET `id_group` = '0' WHERE `users`.`id_group` =".$_POST['group_id'].";";
		$db_result_update = mysql_query($db_query_update) 
			or die("Échec de la suppression d'utilisateur : " . mysql_error());
	}
	
?>
<div class="page"> <!--CONTENU DE LA PAGE -->
<!--AFFICHAGE DE L'INTERFACE--> 				
<h1>SUPPRIMER UN GROUPE</h1>
<p class="avertissement">
	Attention cette opération est irreversible ! le groupe et toute
	les données le concernant seront supprimées. Les utilisateurs 
	affectés à ce groupe n'auront plus de groupe.
</p>
<?php //INTERFACE DE SELECTION DE L'UTILISATEUR
	$db_query="SELECT * FROM groups";
	$db_result = mysql_query($db_query) 
		or die('Échec de la requête : ' . mysql_error());
?>
<form method=post action="./index.php?action=DelGroup">
	<select name="group_id" size="1">
		<?php 	
			while ($line = mysql_fetch_array($db_result, MYSQL_ASSOC))
				echo "<option value=\"".$line["id"]."\">".$line["name"]."</option>";
		?>
	</select>  
	<input type="submit" value="Supprimer" />
</form>

</div>

