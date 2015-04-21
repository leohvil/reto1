<?php
if(isset($insert)) {
	
		$objPoliza->addPoliza($txtPoliza, $txtNombre, $txtImporte, $txtReferencia);
		
	
}
?>
<form name="addPoliza" id="datos" action="?section=poliza&action=add&insert=true" method="post" enctype='multipart/form-data'>
<table style="margin:15px auto 0 auto;">
<tr>
	<td><legend>Poliza: </legend></td><td><input type="text" name="txtPoliza" id="txtPoliza" class="txtPoliza" maxlenght="45" size="45"></td>
</tr>
<tr>
	<td><legend>Nombre: </legend></td><td><input type="text" name="txtNombre" id="txtNombre" class="txtNombre" maxlenght="45" size="45"></td>
</tr>
<tr>
	<td><legend>Importe: </legend></td><td><input  type="text" name="txtImporte" id="txtImporte" class="txtImporte" maxlenght="45" size="45"></td>
</tr>
<tr>
	<td><legend>Referencia: </legend></td><td><input type="text" name="txtReferencia" id="txtReferencia" class="txtReferencia" maxlenght="45" size="45"></td>
</tr>

<tr>
<td></td><td><input type="submit" value="Enviar" class="buttonDetalle"></td>
</tr>
</table>
</form>

