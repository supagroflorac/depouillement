<?php //ON AJOUTE LE GROUPE SI IL EST DEFINIS
	if(isset($_POST['group_name'])) {	
		$db_query = "INSERT INTO `groups` (`id` ,`name`) VALUES (NULL , '".$_POST['group_name']."');";
		$db_result = mysql_query($db_query) 
			or die("Échec de l'ajout du groupe : " . mysql_error());
		warning($_POST['group_name']." a été ajouté avec succès</p></div>");
	}
?>
<div class="page"> <!--CONTENU DE LA PAGE -->
<!--AFFICHAGE DE L'INTERFACE-->
<h1>AJOUTER UN GROUPE</h1>
<form method="post" action="./index.php?action=AddGroup">
	Utilisateur : <input type="text" name="group_name" /> 
	<input type="submit" value="Ajouter" />
</form>
</div>

