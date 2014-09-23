<?php //ON MODIFIE L'UTILISATEUR SI IL EST DEFINIS
	if(isset($_POST['user_id'], $_POST['user_name'])) {	
		$db_query = "UPDATE `users` SET `name` = '".$_POST['user_name']."', `id_group`='".$_POST['id_group']."' WHERE `users`.`id` =".$_POST['user_id'].";";
		print_r($db_query);
		$db_result = mysql_query($db_query) 
			or die("Échec de la suppression d'utilisateur : " . mysql_error());
		warning("L'utilisateur a été modifié avec succès !");
	}
?>
<div class="page"> <!--CONTENU DE LA PAGE -->



<!--AFFICHAGE DE L'INTERFACE--> 				
<h1>MODIFIER UN UTILISATEUR</h1>
<form method="post" action="./index.php?action=ModUser">
	<label for="user_id">Utilisateur à modifier : </label>
	<select name="user_id" id="user_id" size="1">
		<?php
			$db_query_users="SELECT * FROM `users` ORDER BY `name`";
			$db_result_users = mysql_query($db_query_users) 
				or die('Échec de la requête : ' . mysql_error()); 	
			while ($user = mysql_fetch_array($db_result_users, MYSQL_ASSOC))
				echo "<option value=\"".$user["id"]."\">".$user["name"]."</option>";
		?>
	</select>
	<select name="group_id" size="1">
		<option>--sans groupe--</option>
		<?php
			$db_query_groups="SELECT * FROM `groups` ORDER BY `name`";
			$db_result_groups = mysql_query($db_query_groups) 
				or die('Échec de la requête : ' . mysql_error()); 	
			while ($group = mysql_fetch_array($db_result_groups, MYSQL_ASSOC))
				echo "<option value=\"".$group["id"]."\">".$group["name"]."</option>";
		?>
	</select><br />
	<label for="user_name">Nouveau nom : </label><input id="user_name" type="text" name="user_name" />  
	<input type="submit" value="Modifier" />
</form>

</div>
