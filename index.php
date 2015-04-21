<?PHP
require_once("system/requires.files.php");

?>
<html>
<head>
 <title>Reto Nuvem</title>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
 <script type="text/javascript" src="js/functions.js"></script>
 <script type="text/javascript" src="js/formValidations.js"></script>
 <LINK href="css/style.css" rel="stylesheet" type="text/css">   
 </head>
<body>
	<div id="nav">
		<ul id="menu">
			<li><a href="index.php">Inicio</a></li>			
					<li><a href="?section=poliza&action=view">Polizas</a></li>	
				</li>
			</ul>
				
				</div>
				<div id="contenido" class="contenido">
					<div id="bloque">
						<?php
						require_once("inicio.php");
						
						?>
	

	</div>
	</div>

</body>
</html>

