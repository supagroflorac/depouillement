<?php //ON AJOUTE L'UTILISATEUR SI IL EST DEFINIS
	if(isset($_POST['user_name'])) {	
		$db_query_insert = "INSERT INTO `users` (`id` ,`name`, `id_group`) VALUES (NULL , '".$_POST['user_name']."' , '".$_POST['id_group']."');";
		$db_result_insert = mysql_query($db_query_insert) 
			or die("Échec de l'ajout d'utilisateur : " . mysql_error());
		warning($_POST['user_name']." a été ajouté avec succès</p></div>");
	}
?>
<div class="page"> <!--CONTENU DE LA PAGE -->
<!--AFFICHAGE DE L'INTERFACE-->
<h1>AJOUTER UN UTILISATEUR</h1>
<form method="post" action="./index.php?action=AddUser">
	Utilisateur : <input type="text" name="user_name" />
	<select name="id_group" size="1" value="0">
		<option value="0" selected="selected">--sans groupe--</option>
<?php //On recupere la liste des groupes
			$db_query_groups = "SELECT * FROM `groups` ORDER BY `name`";
			$db_result_groups = mysql_query($db_query_groups) 
				or die("Échec de lors de la recuperation de la liste des groupes : " . mysql_error());
			while ($group = mysql_fetch_array($db_result_groups, MYSQL_ASSOC))
				echo "\t\t<option value=\"".$group["id"]."\">".$group["name"]."</option>\n";
		?>
	</select>
	<input type="submit" value="Ajouter" />
</form>
</div>
