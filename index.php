<?php //CHARGEMENT DE L'ENTETE
	include("./src/header.php");
?>

<?php //CONNECTION A LA BASE DE DONNÉES
include("./cfg/cfg.php");
$db_link = mysql_connect($db_server, $db_user, $db_password)
    or die('Impossible de se connecter : ' . mysql_error());

//$db_link = mysql_connect("mysql5-15", "cdrfloradep", "depouille48")
//    or die('Impossible de se connecter : ' . mysql_error());

mysql_select_db($db_base) 
	or die('Impossible de sélectionner la base de données');
?>

<div class="masque_menu"> </div> <!-- permet de conserver l'emplacement du menu -->

<div class="menu"> <!--MENU DE LA PAGE? -->
	<ul>
		<li><a href="./index.php">Accueil</a></li>
		<li><a href="./index.php" target="_blank">Pleine page</a></li>
		<li><span class="print" onClick="javascript:print();">Imprimer</span></li>
		<li><a class="lock" href="./admin/index.php">Administrer</a></li>
	</ul>
</div>

<div class="page"> <!--CONTENU DE LA PAGE -->
	<?php //CHOIX UTILISATEUR
		$db_query="SELECT * FROM users ORDER BY `name`";
		$db_result = mysql_query($db_query) 
			or die('Échec de la requête : ' . mysql_error());
	?>
	
	<?php if(!isset($_GET['id_user'])){ ?>
	<h1>Choix du rapport à afficher</h1>
			
	
	
	
	<form method="get" action="./index.php">
		<select name="id_user" size="1"> 
			<?php 	
				while ($line = mysql_fetch_array($db_result, MYSQL_ASSOC))
					echo "<option value=\"".$line["id"]."\">"./*utf8_encode(*/$line["name"]/*)*/."</option>";
			?>
		<?php $months = array( '01'=>'', '02'=>'', '03'=>'', ''=>'', ''=>'', ''=>'', ''=>'', ''=>'', ''=>'', ''=>'', ''=>'', ''=>'') ?>
		</select>
			<select name="month" size="1">
			<option value="01" <?php if (date("m") == "02") echo "selected=\"selected\"" ?>>Janvier</option>
			<option value="02" <?php if (date("m") == "03") echo "selected=\"selected\"" ?>>Février</option>
			<option value="03" <?php if (date("m") == "04") echo "selected=\"selected\"" ?>>Mars</option>
			<option value="04" <?php if (date("m") == "05") echo "selected=\"selected\"" ?>>Avril</option>
			<option value="05" <?php if (date("m") == "06") echo "selected=\"selected\"" ?>>Mai</option>
			<option value="06" <?php if (date("m") == "07") echo "selected=\"selected\"" ?>>juin</option>
			<option value="07" <?php if (date("m") == "08") echo "selected=\"selected\"" ?>>Juillet</option>
			<option value="08" <?php if (date("m") == "09") echo "selected=\"selected\"" ?>>Août</option>
			<option value="09" <?php if (date("m") == "10") echo "selected=\"selected\"" ?>>Septembre</option>
			<option value="10" <?php if (date("m") == "11") echo "selected=\"selected\"" ?>>Octobre</option>
			<option value="11" <?php if (date("m") == "12") echo "selected=\"selected\"" ?>>Novembre</option>
			<option value="12" <?php if (date("m") == "01") echo "selected=\"selected\"" ?>>Décembre</option>			
		</select>
		<input type="test" name="year" value="<?php echo date("Y")?>" size="4" maxlength="4"> 
		<input type="submit" value="Voir" />
	</form>
	
	<?php } ?>
		
	<?php //AFFICHAGE RAPPORT
		if(isset($_GET['id_user'])){
			include("./src/report.php");
		}
	?>
</div>

<?php //DÉCONNECTION BASE DE DONNÉES
	mysql_close($db_link);
?>

<?php //CHARGEMENT DU PIED DE PAGE
	include("./src/footer.php");
?>
