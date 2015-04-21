<?php

class Polizas {
	//funcion  crear poliza
	function addPoliza($poliza, $nombre, $importe, $referencia){
		$sql = "INSERT INTO poliza_ref(poliza, nombre, importe, referencia) Values('$poliza', '$nombre', '$importe', '$referencia')";
		if($this->checkPoliza($nombre) == TRUE){
			echo "<h2>Ya existe una poliza con este nombre</h2>";
			}
			else{
				$query = mysql_query($sql) or die ("Error: " . mysql_error());
				if($query) {
					echo "<h2>Se ha agregado exitosamente la poliza</h2>";
				}
				else {
					echo "<h2>No se ha podido agregar la poliza, inténtalo más tarde <br> Detalle: </h2>" . mysql_error();
					}
				}
			}

	//Función elaborada para  no repetir nombres de polizas.
	function checkPoliza($nombre) {
		$sql = "SELECT nombre FROM poliza_ref WHERE nombre = '$nombre'";
		$query = mysql_query($sql) or die("Error: " . mysql_error());
		if(mysql_num_rows($query)>0) {
			return TRUE;
		}
		else {
			return FALSE;
			}
	}
	
//funcion ver poliza
	function viewPoliza() {
		$sql = "SELECT * FROM poliza_ref";
		$query = mysql_query($sql) or die("Error: " . mysql_error());
		if($query) {
			while($row=mysql_fetch_assoc($query)) {
				
				echo "<tr>";
				echo "<td>" . $row['poliza'] . "</td>";
				echo "<td>" . $row['nombre'] . "</td>";
				echo "<td>" . $row['importe'] . "</td>";
				echo "<td>" . $row['referencia'] . "</td>";
				echo "<td><a href=\"?section=poliza&action=edit&id=" . $row['poliza'] . "\">Editar</a>";
				echo "<td><a href=\"?section=poliza&action=del&id=" . $row['poliza'] . "\">Eliminar</a>";
				echo "<td><a href=\"?section=poliza&action=pagar&id=" . $row['poliza'] . "\">Pagar</a>";
				echo "</tr>";
				
			}
		}
	}

	// obtener la poliza
	function getPoliza($idPoliza) {
		$sql = "SELECT * FROM poliza_ref WHERE poliza = '$idPoliza'";
		$query = mysql_query($sql) or die("Error: " . mysql_error());
		if($query) {
			$row = mysql_fetch_assoc($query);
			return $row;
		}
	}

	//actualizar la poliza
	function updatePoliza($idPoliza,$nombre,$importe,$referencia){
	
		$sql = "UPDATE poliza_ref SET nombre='$nombre', importe='$importe', referencia ='$referencia' WHERE poliza = '$idPoliza'";
		$query = mysql_query($sql) or die("Error: " . mysql_error());
			if($query) {
				?>
              <script type="text/javascript">
              location.href ="?section=poliza&action=view";
              alert("Poliza editada correctamente")
              </script>
            <?php
			}
			else {
				echo "<h2>No se ha podido actualizar la poliza, inténtalo más tarde <br> Detalle:</h2> " . mysql_error();
			}
		}
	//elimninar la poliza
	function delPoliza($id) {
		$sql = "DELETE FROM poliza_ref WHERE poliza = '$id'";
		$query = mysql_query($sql) or die("Error: " . mysql_error());
		if($query) {
			?>
			<script>alert("Se eliminó correctamente la poliza")</script>
			<?php
		}
	}

	function pagoPoliza($id){
		$sql = "SELECT * FROM poliza_ref WHERE poliza = '$id'";
		$query =  mysql_query($sql) or die("Error: " . mysql_error());
		if ($query) {
		$row = mysql_fetch_assoc($query);
		?>
		<form action="https://www.egbs1.com.mx/eEmpresa/metlife/principal/validaReferencias.jsp" method="post" name="forma">

    <input type="hidden" value="166" name="t_servicio"></input>
    <table>
       <tbody>
        <tr>
            <td>
            	Nombre
            </td>
            <td>
                <input type="text" value="<?php echo  $row['nombre']; ?>" name="n_cliente" maxlength="50" size="30"></input>
            </td>
        </tr>
        <tr>
        <td>
        	Referencia
        </td>
    <td>
        <input type="text" value="<?php echo  $row['referencia']; ?>" style="TEXT-ALIGN: left" maxlength="50" size="30" name="c_referencia"></input>
    </td>
</tr>

<tr>

    <td>

        Importe

    </td>
    <td>
        <input type="text" value="<?php echo  $row['importe']; ?>" style="TEXT-ALIGN: left" name="t_importe"></input>
    </td>
</tr>
        </tbody>
    </table>
 
    <input type="submit" value="Ir a Pago" name="Submit" class="buttonDetalle" target="_blank"></input>

</form>

        <?php
		/*echo "<tr>";
		echo "<td>" . $row['poliza'] . "</td>";
		echo "<td>" . $row['nombre'] . "</td>";
		echo "<td>" . $row['importe'] . "</td>";
		echo "<td>" . $row['referencia'] . "</td>";
		echo "</tr>";*/

		
		}
	}

}

	
?>