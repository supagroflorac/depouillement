<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="fr">
<head>
	<title>Dépouillement des revues</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="../css/depouillement.css" />
	
	<!-- Fonctions javascripts -->
	<script  type="text/javascript">
		function checkAll (id_conteneur)
		{
			var Chckbox = document.getElementById(id_conteneur).firstChild;
			while (Chckbox!=null) {
				if (Chckbox.nodeName=="INPUT"){
					if (Chckbox.getAttribute("type")=="checkbox") {
						document.getElementById(Chckbox.getAttribute("id")).checked=1;
						
					 }
				}
				Chckbox = Chckbox.nextSibling;
			}
		}
		
		function checkNone (id_conteneur) { 
			var Chckbox = document.getElementById(id_conteneur).firstChild; 
			while (Chckbox!=null) { 
				if (Chckbox.nodeName=="INPUT"){ 
					if (Chckbox.getAttribute("type")=="checkbox") { 
						document.getElementById(Chckbox.getAttribute("id")).checked=0; 
						
					 }
				}
				Chckbox = Chckbox.nextSibling; 
			}
		}
	</script>
	
</head>
<body>

