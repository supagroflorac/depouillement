<?php //CHARGEMENT DE L'ENTETE
	include("./src/header.php");
	include("./src/functions.php");
?>

<?php //CONNECTION A LA BASE DE DONNÉES
include("../cfg/cfg.php");
$db_link = mysql_connect($db_server, $db_user, $db_password)
    or die('Impossible de se connecter : ' . mysql_error());

mysql_select_db($db_base) 
	or die('Impossible de sélectionner la base de données');
?>

<div class="masque_menu"> </div> <!-- permet de conserver l'emplacement du menu -->

<div class="menu"> <!--MENU DE LA PAGE? -->
	<ul>  
		<li><a href="../index.php">Retour</a></li>
		<li><span class="subMenu">Utilisateurs</span>
			<ul class="subMenu">
				<li><a href="./index.php?action=AddUser" class="add">Ajouter</a></li>
				<li><a href="./index.php?action=DelUser" class="del">Supprimer</a></li>
				<li><a href="./index.php?action=ModUser" class="mod">Modifier</a></li>
			</ul>
		</li>
		<li ><span class="subMenu">Groupes</span>
			<ul class="subMenu">
				<li><a href="./index.php?action=AddGroup" class="add">Ajouter</a></li>
				<li><a href="./index.php?action=DelGroup" class="del">Supprimer</a></li>
			</ul>
		</li>
		<li><span class="subMenu">Articles</span>	
			<ul class="subMenu"> 
				<li><a href="./index.php?action=AddArticle" class="add">Ajouter</a></li>
				<li><a href="./index.php?action=DelArticle" class="del">Supprimer</a></li>
				<li><a href="./index.php?action=ModArticle" class="mod">Modifier</a></li>
			</ul>
		</li>
	</ul>
</div>

<?php
	/*if(!isset($_GET['action'])) {
		exit;
	}*/
	switch($_GET['action']){
	case "AddUser" : 				
			include("./src/AddUser.php");
		break;
	case "DelUser" :
			include("./src/DelUser.php");
			break;
	case "ModUser" : 				
			include("./src/ModUser.php");
		break;
	case "AddArticle" : 				
			include("./src/AddArticle.php"); 		
		break;
	case "DelArticle" : 				
			include("./src/DelArticle.php"); 		
		break;
	case "ModArticle" : 				
			include("./src/ModArticle.php"); 		
		break;
	case "AddGroup" : 				
			include("./src/AddGroup.php"); 		
		break;
	case "DelGroup" : 				
			include("./src/DelGroup.php"); 		
		break;
	}
?>



<?php //DÉCONNECTION BASE DE DONNÉES
	mysql_close($db_link);
?>

<?php //CHARGEMENT DU PIED DE PAGE
	include("./src/footer.php");
?>
