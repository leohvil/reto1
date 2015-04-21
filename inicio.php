<?PHP
		$section ="";
		extract($_REQUEST);
		switch($section) {
				
			case "poliza":
				require_once("main/polizas/poliza.php");
				break;
		
			default:
				break;
		}

?>
