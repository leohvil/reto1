<?PHP
$ServidorC="localhost"; $UsuarioC="root"; $PassC="";
$DataBaseC="retodb";

//$ServidorC="ocotlan.db.6697365.hostedresource.com"; $UsuarioC="ocotlan"; $PassC="Password09!";
//$DataBaseC="ocotlan";


$connect=mysql_connect($ServidorC,$UsuarioC,$PassC) or die("Error en la conexión: " . mysql_error());
$res=mysql_select_db($DataBaseC,$connect) or die("Un error ha ocurrido, 
	contacta a tu administrador de servicios.<br> Detalle error: " . mysql_error())
?>