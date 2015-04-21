<?php
extract($_REQUEST);
if(isset($update)) {
	
		$objPoliza->updatePoliza($hdnPoliza, $txtNombre, $txtImporte, $txtReferencia);
		
	
}
$arrayPoliza = $objPoliza->getPoliza($id);
extract($arrayPoliza);

?>
<form name="editPoliza" id="datos" action="?section=poliza&action=edit&update=true&id=<?PHP echo $poliza;?>" method="post" enctype='multipart/form-data'>
<table style="margin:15px auto 0 auto;">
<input type="hidden" name="hdnPoliza" value="<?PHP echo $poliza; ?>">
<tr>
	<td><legend>Nombre: </legend></td><td><input type="text" name="txtNombre" id="txtNombre" class="txtNombre" value="<?PHP echo $nombre;  ?>" maxlenght="45" size="45"></td>
</tr>
<tr>
	<td><legend>Importe: </legend></td><td><input  type="text" name="txtImporte" id="txtImporte" class="txtImporte" value="<?PHP echo"$importe" ?>" ></td>
</tr>
</tr>
<tr>
	<td><legend>Referencia: </legend></td><td><input type="text" name="txtReferencia" id="txtReferencia" class="txtReferencia" value="<?php echo $referencia; ?>" maxlenght="45" size="45"></td>
</tr>

<tr>
<td></td><td><input type="submit" value="Enviar" class="buttonDetalle"></td>
</tr>
</table>
</form>
