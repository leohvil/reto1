<?PHP
$action = "";
extract($_REQUEST);
$objPoliza = New Polizas();
switch ($action) {
	case 'add':
		require_once("main/polizas/addpoliza.php");
		break;
	case 'edit':
		require_once("main/polizas/editpoliza.php");
		break;
	case 'view':
		require_once("main/polizas/viewpoliza.php");
		break;
	case 'del':
		require_once("main/polizas/delpoliza.php");
		break;
	case 'pagar':
		require_once("main/polizas/pagoView.php");
		break;
	default:
		require_once("main/polizas/addpoliza.php");
		break;
}

?>