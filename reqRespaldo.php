<? 
session_start();
include "../../conexion.php";
include_once "../../clases/db.man.leshuga.php";

include "productos_incl.php";

$corte_c = "select id_corte from caja_corte where id_sucursal = '".$_SESSION[usuario][id_sucursal]."' and estado = '1'";
$corte_b = mysql_query($corte_c,$conexion);
$corte_a = mysql_fetch_array($corte_b);
$_SESSION[corte][id] = $corte_a[id_corte];
$_SESSION[usuario][id];
$_POST[id_sucursal] = $_SESSION[usuario][id_sucursal];
$dbman = new dbManLeshuga();

$f = date("Y-m-d");
$fechahoy = date("d-m-Y");
$fechaa = date("Y", strtotime($f));
$desde = date("Y-01-01");
$hasta = date("Y-m-d");


/*if($_POST[enviar_post] == 1)
{
for($x=1;$x<=$_POST[aux_impuestos] - 1;$x++)
	{
			 $impuestos2_c = "select percent from impuestos where id_impuesto = '".$_POST['Imp'.$x]."'";
			 $impuestos2_b = mysql_query($impuestos2_c,$conexion);
			 $impuestos2_a = mysql_fetch_array($impuestos2_b); 
			 $suma = ($_POST[Precio] * ($impuestos2_a[percent] / 100));
			 $sub_total =  $sub_total + $suma;
			 $_POST[Incluye_imp] == 1;		
	}
}*/

//-----Finaliza y guarda la entrada-----//
if($_POST[Finalizar])
	{
	$idcli = explode(" - ",$_POST[buscarcliente]); 
	$idcli1=$id_cli[0];
	$fecha = date("Y-m-d H:i:s");
	
	$idejec = explode(" - ",$_POST[ejecutivo]); 
	$idejec1=$idejec[0];
		
	$cliente_bus="select * from clientes where id_cliente='$idcli1'";
	$cliente_bus1=mysql_query($cliente_bus,$conexion);
	$cliente_num=mysql_num_rows($cliente_bus1);
	if($cliente_num!=1)
	{//si es cliente nuevo
		$c_clientes = "insert into clientes(razonsocial,rfc,calle,no_ext,no_int,colonia,cp,cd,dom_estado,pais, 																																					                    estado,id_sucursal,email)
				values(ucase('$_POST[razonsocial]'),ucase('$_POST[rfc]'),ucase('$_POST[calle]'),ucase('$_POST[no_ext]'),ucase('$_POST[no_int]'),ucase('$_POST[colonia]'),'$_POST[cp]',ucase('$_POST[ciudad]'),ucase('$_POST[dom_estado]'),'Mexico','1',ucase('$_POST[id_sucursal]'),lcase('$_POST[email]'))"; 
				mysql_query($c_clientes,$conexion);
				$idcli1=mysql_insert_id($conexion);
		}
		else
		{
			//si hay cliente seleccionado se actualizan datos
		$c_clientes = "update clientes set
					razonsocial = ucase('$_POST[razonsocial]'),
					rfc = ucase('$_POST[rfc]'),
					calle = '$_POST[calle]',
					no_ext = ucase('$_POST[no_ext]'),
					no_int = ucase('$_POST[no_int]'),
					colonia = ucase('$_POST[colonia]'),
					cp = $_POST[cp],
					cd = ucase('$_POST[ciudad]'),
					dom_estado = ucase('$_POST[dom_estado]'),
					email=lcase('$_POST[email]')
					where id_cliente = $idcli1"; 
			mysql_query($c_clientes,$conexion);
			
			}
	if($idcli1>0)
	{		
    	$c_requisicion = "Insert into requisicion set
	               cliente = '".$idcli1."',
				  fecha = '$f',
				  id_vendedor = '".$idejec1."',
				  total_unidades ='".$_POST[unidades]."',
				  total ='".$_POST[Total]."',
				  notas ='".$_POST[notas]."',
				  id_solicita ='".$_SESSION[usuario][id]."',
				   id_sucursal='".$_POST[id_sucursal] ."',
				  estatus ='1',
				  id_venta='0',
				  id_linea='".$_POST[id_linea]."'";
					  
		mysql_query($c_requisicion, $conexion);

		$ID = mysql_insert_id();
		//$ultimoIDRow = $ID;	
			
		//-----Guarda el detalle de la entrada-----//
		for($i=1;$i<=$_SESSION[entrada][contador];$i++)
		{		  		 		    						 		  
		 $c_requisicion_detalle = "Insert into requisicion_detalle set 
				id_requisicion = '".$ID."',
				producto = '".$_SESSION[entrada][$i][producto]."',
				modelo = '".$_SESSION[entrada][$i][modelo]."',
				watts = '".$_SESSION[entrada][$i][watts]."',
				kelvins = '".$_SESSION[entrada][$i][kelvins]."',
				observaciones = '".$_SESSION[entrada][$i][observaciones]."',
				cantidad = '".$_SESSION[entrada][$i][cantidad]."',
				precio_unitario = '".$_SESSION[entrada][$i][precio]."',
				precio_unit_imp = '".$_SESSION[entrada][$i][precio_imp]."',
				merca_comprar_recibir = '0'";
				
					mysql_query($c_requisicion_detalle, $conexion);					
			
			}	


		//-----Limpia la entrada actual después de guardarla-----//
		for($i=1;$i<=$_SESSION[entrada][contador];$i++)
		{
		$_SESSION[entrada][$i][cantidad] = "";
		$_SESSION[entrada][$i][producto] = "";
		$_SESSION[entrada][$i][modelo] = "";
		$_SESSION[entrada][$i][watts] = "";
		$_SESSION[entrada][$i][kelvins] = "";
		$_SESSION[entrada][$i][observaciones] = "";
		$_SESSION[entrada][$i][modelo] = "";
		$_SESSION[entrada][$i][imagen] = "";
		$_SESSION[entrada][$i][precio] = "";
		$_SESSION[entrada][$i][precio_imp] = "";
		$_SESSION[entrada][$i][imagen] = "";		
		}
		unset($_POST[Cancelar]);
		unset($_POST[Producto]);
		$_SESSION[entrada][contador] = 0;
	
		echo "<script>
		alert('La requisicion ha sido guardada.'); 
		document.location='requisicion_consulta.php'</script>";
	}
	else
	{
		echo "<script>alert('Favor de Elegir un Cliente o Registrar uno nuevo');</script>";
		}
}

//-----Agrega detalle a la entrada-----//
if($_POST[Agregar] == 1)
	{
		
	
	if($_POST[Cantidad] == "" || !$_POST[Cantidad] || $_POST[Cantidad] == 0) { $_POST[Cantidad] = 1; } 
	if($_POST[Pzas] == "" || !$_POST[Pzas] || $_POST[Pzas] == 0) { $_POST[Pzas] = 1; } 
	$p = $_SESSION[entrada][contador] + 1; 
	
	
	$_SESSION[entrada][$p][imagen] = $_POST[producto]."-". $_POST[modelo]."-". $_POST[watts]."-". $_POST[kelvins]."-".$f.".jpg";
	
	$archivo = $_FILES[file][tmp_name];
		//echo $archivo;
		if ($archivo != "")
			{
			$nombre_fotoc = $_SESSION[entrada][$p][imagen]; 

			copy($archivo, "catalogo/$nombre_fotoc") or die("Imposible enviar archivo");
			$tamanioc = getimagesize("catalogo/$nombre_fotoc");
			if($tamanioc[0] >= $tamanioc[1]) 	
			{ 	$porcentajec = (((100 / $tamanioc[0]) * $a_config[chica]) / 100);	}
			else { 	$porcentajec = (((100 / $tamanioc[1]) * $a_config[chica]) / 100);  		}
						
			$ancho = round($tamanioc[0] * $porcentajec);
			$alto = round($tamanioc[1] * $porcentajec);
			resize("catalogo/$nombre_fotoc","catalogo/$nombre_fotoc",$ancho,$alto,$tamanioc[2]);
			
			}
	
	$_SESSION[entrada][$p][cantidad] = $_POST[Cantidad];
	$_SESSION[entrada][$p][producto] = $_POST[producto];
	$_SESSION[entrada][$p][modelo] = $_POST[modelo];
	$_SESSION[entrada][$p][watts] = $_POST[watts];
	$_SESSION[entrada][$p][kelvins] = $_POST[kelvins];
	$_SESSION[entrada][$p][observaciones] = $_POST[Observa];
	
	$_SESSION[entrada][$p][precio] = $_POST[Precio];
	$_SESSION[entrada][$p][precio_imp] = $_POST[Precio] * $_POST[Cantidad];
	
	
	$_SESSION[entrada][contador] = $_SESSION[entrada][contador] + 1;
	unset($_POST[Producto]);
	$_POST[Incluye_imp]=0;
	
	$_POST[Cantidad] = "";
    $_POST[producto] = "";
	$_POST[modelo] = "";
	$_POST[watts] = "";
	$_POST[kelvins] = "";
	$_POST[Precio] = "";
	$_POST[Precio_imp] = "";
	}

if($_POST[cancelar2])
	{
	for($i=1;$i<=$_SESSION[entrada][contador];$i++)
		{
		$_SESSION[entrada][$i][cantidad] = "";
		$_SESSION[entrada][$i][producto] = "";
		$_SESSION[entrada][$i][modelo] = "";
		$_SESSION[entrada][$i][watts] = "";
		$_SESSION[entrada][$i][kelvins] = "";
		$_SESSION[entrada][$i][observaciones] = "";
		$_SESSION[entrada][$i][modelo] = "";
		$_SESSION[entrada][$i][imagen] = "";
		$_SESSION[entrada][$i][precio] = "";
		$_SESSION[entrada][$i][precio_imp] = "";
		$_SESSION[entrada][$i][imagen] = "";
		}
	unset($_POST[Cancelar]);
	unset($_POST[Producto]);
	$_SESSION[entrada][contador] = 0;
	?><script> location = "requisicion_consulta.php"; </script><?
	}
	
	
?>
<? 
	  $fecha1 = date("Y-m-d");
	  
		$c_dolar = "select valor from tipo_de_cambio where fecha = '".$fecha1."'";
		$r_dolar = mysql_query($c_dolar,$conexion);
		
		if(mysql_affected_rows() > 0){
			$a_dolar = mysql_fetch_array($r_dolar);
		}else{
			
			$rs = mysql_query("SELECT MAX(id_tipo) AS id FROM tipo_de_cambio");
				if ($row = mysql_fetch_row($rs)) {
				$id_tipo_cambio = trim($row[0]);
			}
			
			$c_dolar = "select fecha,valor from tipo_de_cambio where id_tipo = ".$id_tipo_cambio;
			$r_dolar = mysql_query($c_dolar,$conexion);
			$a_dolar = mysql_fetch_array($r_dolar);
			$fecha_tipo_cambio = $a_dolar['fecha'];
			
				
		}
		
		$_SESSION[venta][tipo_cambio] = $a_dolar[valor];
		?>
<html>
<head>
<title>:: Punto de Venta ::</title>
<link rel="stylesheet" type="text/css" href="../css/default.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">

body {
	/* background-image: url(img/fondo_degradado.jpg); */
}
.Estilo5 {font-size: 10px}
.Estilo10 {font-size: 10}
.Estilo11 {font-size: 12px}
.Estilo14 {
	font-size: 14px;
	font-weight: bold;
}
.Estilo15 {font-size: 10px; color: #333333; font-weight: bold; }
.Estilo16 {color: #333333; font-size: 10px; }
.Estilo17 {color: #333333}
.Estilo18 {font-size: 12px; color: #333333; font-weight: bold; }
.Estilo20 {font-size: 10px; color: #FFFFFF; font-weight: bold; }
.Estilo22 {font-size: 12px; color: #FFFFFF; font-weight: bold; }
.Estilo23 {color: #FFFFFF}


			.suggest_link {
				background-color: #FFFFFF;
				padding: 2px 6px 2px 6px;
			}
			.suggest_link_over {
				background-color: #7DBEFF;
				padding: 2px 6px 2px 6px;
			}
			#search_suggest {
			position: absolute;
			background-color: #FFFFFF;
			text-align: left;
			font-size:12px;
			border: 1px solid #000000;
			width: 700;
			visibility: hidden;
			left: 98px;
	
			}	
			.suggest_link2 {
		background-color: #FFFFFF;   
		padding: 2px 6px 2px 6px;
	}
	.suggest_link_over2 {
		background-color: #7DBEFF;
		padding: 2px 6px 2px 6px;
	}
			#search_cliente {
	position: absolute;
	background-color: #FFFFFF;
	text-align: left;
	font-size:12px;
	border: 1px solid #000000;

	width: 446px;

	visibility: hidden;
			}
		#search_vendedor {
	position: absolute;
	background-color: #FFFFFF;
	text-align: left;
	font-size:12px;
	border: 1px solid #000000;

	width: 300px;

	visibility: hidden;
			}			

</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript" src="ajax_search.js"></script>
<script language="JavaScript" type="text/javascript" src="ajax_buscar.js"></script>
<script language="JavaScript" type="text/JavaScript">
function calcular_iva()
{
	
		//*****************SIN IMPUESTOS DINAMICOS
		
		if(document.getElementById('Precio').value == '')
		document.getElementById('Precio').value = 0;
		
	var iva = (parseFloat(document.getElementById('percent').value)/100)+1;
	var costo_iva = parseFloat(document.getElementById('Precio').value) * iva;
	document.form1.iva.value = Math.round((document.getElementById('Precio').value) + costo_iva );
	//*********************************
				
	var subtotal2 = 0;
	//*********************************IMPUESTOS DINAMICOS
	if(document.getElementById('Incluye_imp').value == 1 && document.getElementById('Precio').value > 0) 
		{
		var aux = parseInt(document.form1.aux_impuestos.value);
		//alert('valor inicial'+ aux);
		var aux2 = 0;
		//aux = aux -1;
			for(var aux2 = 1; aux2 < aux; aux2++)
			{
				//alert('valor en ciclo aux* '+ aux);
				
				//alert('valor en ciclo aux2* '+ aux2);
				if(document.getElementById('check'+ aux2).checked == true && document.getElementById('Imp'+ aux2).value != 0)
				{
					var cadena2 = document.getElementById('Imp'+ aux2).value.split("-");
					document.getElementById('imp_percent'+ aux2).value = cadena2[1];
					
						//calcula iva administrativo***************************************************
						if(document.getElementById('Precio').value == '')
						document.getElementById('Precio').value = 0;
		
						var iva_admon1 = (parseFloat(document.getElementById('percent').value)/100)+1;
						var costo_iva1 = parseFloat(document.getElementById('Precio').value) * iva_admon1;
						document.form1.total2.value = Math.round(costo_iva1);
						var total1 = parseFloat(document.getElementById('Precio').value) + costo_iva1;							
	                    document.form1.total.value = Math.round(total1);
				}
				//else
				//document.getElementById('imp_percent'+ aux2).value = '';
			}
		}				
}
//******************+++++++++++++++*******************+++++++++++++************
function oculta_impuestos()
{	
	document.getElementById('tabla_impuestos').style.display = 'none';
	document.getElementById('botones_impuestos').style.display = 'none';
	document.getElementById('botones_costos').style.display = '';
	if(document.form1.aux_button.value == 0 || document.form1.aux_button.value == 2)
	{
		document.getElementById('tabla_impuestos').style.display = 'none';
		document.getElementById('botones_impuestos').style.display = 'none';
		document.getElementById('botones_costos').style.display = '';

		document.form1.aux_button.value = 1;
	}
	else
	{
		document.getElementById('tabla_impuestos').style.display = '';
		document.getElementById('botones_impuestos').style.display = '';
		document.getElementById('botones_costos').style.display = 'none';
		
		document.form1.aux_button.value = 0;
	}
	//	document.form1.Contrasena.value = '********';
	//	document.form1.Contrasena2.value = '';
	
	//document.form1.Contrasena.type = 'button';
}

function muestra_impuestos33()
{
	document.getElementById('tabla_impuestos').style.display = 'none';
	document.getElementById('botones_impuestos').style.display = 'none';
	document.getElementById('botones_costos').style.display = '';
	//document.form1.Contrasena.type = 'password';
		if(document.form1.aux_button.value == 1)
	{
		alert('muestra impuestos');
		document.getElementById('tabla_impuestos').style.display = '';
		document.getElementById('botones_impuestos').style.display = '';
		document.getElementById('botones_costos').style.display = 'none';
		//document.getElementById('Imp1').focus();
		document.form1.aux_button.value = 0;
	}
	else if(document.form1.aux_button.value == 0)
	{
		document.getElementById('tabla_impuestos').style.display = 'none';
		document.getElementById('botones_impuestos').style.display = 'none';
		document.getElementById('botones_costos').style.display = '';

		document.form1.aux_button.value = 1;
	}

	//	document.form1.Contrasena.value = '';
	//	document.form1.Contrasena2.value = '';
}
function envio(e)
{
	//document.getElementById('Guardar').disabled = true;
	var del = confirm('¿Está seguro que desea guardar el producto?');
	if(del)
			document.form1.submit();
		else
		{
			//document.getElementById('Guardar').disabled = false;
		e.returnValue = false;
		
		}
	}
	
function suma_cargos(){
	var i,n=0,t,s;
	for(i=0;i<14;i++){
		n = parseFloat(n) + parseFloat(document.getElementById("extra_"+i).value);
	}
	var t = parseFloat(document.getElementById("Total_u").value);
	var s = Math.round((t+n)*100)/100;
	
	document.getElementById("Total").value = parseFloat(s);
	document.getElementById("lblTotal").innerHTML = "$" + String(s);
}
//-->

function oculta_boton()
{
if(document.form1.busqueda.value != '')	
	{
	document.getElementById('muestra1').style.display = '';
	document.getElementById('muestra2').style.display = '';
	document.getElementById('muestra3').style.display = '';
	//document.getElementById('muestra4').style.display = '';
	document.getElementById('muestra5').style.display = '';
	findPosX();
	}
	else
	{
	document.getElementById('muestra1').style.display = 'none';
	document.getElementById('muestra2').style.display = 'none';
	document.getElementById('muestra3').style.display = 'none';
	//document.getElementById('muestra4').style.display = 'none';
	document.getElementById('muestra5').style.display = 'none';
	}
	
}
</script>


<script src="../../includes/jquery-1.4.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery.fn.generaOtros = function(indice2){
   $(this).each(function(){
      elem = $(this);
      //elem.data("etiqueta",etiqueta);
      //elem.data("nombreCampo",nombreCampo);
      elem.data("indice2",indice2);
	  //elem.data("indice_aux2", indice_aux2);
	  //elem.data("nombreCampo2",nombreCampo2);
      
      elem.click(function(e){
         e.preventDefault();
         elem = $(this);
         //etiqueta = elem.data("etiqueta");
         //nombreCampo = elem.data("nombreCampo");
         indice2 = elem.data("indice2");
		 //indice_aux2 = elem.data("indice_aux2");
		 //nombreCampo2 = elem.data("nombreCampo2");
		 texto_insertar2 ='<td colspan="2" align="center" class="Estilo5"><input onClick="calcular_iva();" checked id="check' + indice2 + '" type="checkbox" name="check' + indice2 + '" value="' + indice2 + '"><select onChange="document.form1.enviar_post.value = 1;document.form1.submit();" name="Imp' + indice2 +'" class="text1" id="Imp' + indice2 +'"><option value="0">-- Seleccione una Opci&oacute;n --</option> <? $consulta2 = "Select * FROM impuestos  order by descripcion";$resultado2 = mysql_query($consulta2,$conexion);?> <? while($arreglo2 = mysql_fetch_array($resultado2))  { ?><option value =  "<? echo $arreglo2['id_impuesto'];?>" > <? echo utf8_encode($arreglo2['descripcion']." (".$arreglo2['percent']."% )");    ?> </option><? }?></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" name="imp_percent'+ indice2 +'" id="imp_percent'+ indice2 +'" value=""></td>';
         indice2 ++;
		 //indice_aux2 ++;
         elem.data("indice2",indice2);
		 //elem.data("indice_aux2",indice_aux2);
		 //document.getElementById('tramite').value = indice_aux2 - 1;
		 document.getElementById('aux_impuestos').value = indice2;
         nuevo_campo2 = $(texto_insertar2);
         elem.before(nuevo_campo2);
      });
   });
   return this;
} 
$(document).ready(function(){
	var aux = document.getElementById('aux_impuestos').value;
   $("#otros").generaOtros(aux);
}); 
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function findPosX()
  {
	var obj = document.getElementById('busqueda');
    var curleft = 0;
    if(obj.offsetParent)
        while(1)
        {
          curleft += obj.offsetLeft;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
		curleft = curleft + 1;

	  var obj = document.getElementById('busqueda');
    var curtop = 0;
    if(obj.offsetParent)
        while(1)
        {
          curtop += obj.offsetTop;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
		
    //return curtop;
	//************Cambiar valores DIV
	var divLeft = parseInt(curleft) - 1;
	document.getElementById('search_suggest').style.left = divLeft;
	var divTop = parseInt(curtop) + 23;
	document.getElementById('search_suggest').style.top = divTop;	
  }
function habilita() {
  if(form1.Efectivo.value > 0 && form1.Tipo_Pago.value != 0) {
    form1.Finalizar.disabled = false;
  }
  if(form1.Efectivo.value == "" || form1.Efectivo.value == 0 || form1.Tipo_Pago.value == 0 ) {
    form1.Finalizar.disabled = true;
  }
}
function findPosX3()
  {
	var obj = document.getElementById('ejecutivo');
    var curleft = 0;
    if(obj.offsetParent)
        while(1)
        {
          curleft += obj.offsetLeft;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
		curleft = curleft + 1;

	  var obj = document.getElementById('ejecutivo');
    var curtop = 0;
    if(obj.offsetParent)
        while(1)
        {
          curtop += obj.offsetTop;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
		
    //return curtop;
	//************Cambiar valores DIV
	var divLeft = parseInt(curleft) - 1;
	document.getElementById('search_vendedor').style.left = divLeft;
	var divTop = parseInt(curtop) + 23;
	document.getElementById('search_vendedor').style.top = divTop;	
  }

function habilita2() {
  if(form1.Efectivo.value > 0 && form1.Tipo_Pago.value != 0 && form1.Lugar.value != 0 && form1.Comensales.value > 0) {
    form1.Finalizar.disabled = false;
  }
  if(form1.Efectivo.value == "" || form1.Tipo_Pago.value == 0 || form1.Lugar.value == 0 || form1.Comensales.value == "" || form1.Comensales.value == 0) {
    form1.Finalizar.disabled = true;
  }
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' debe contener una dirección válida de correo.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' debe contener un valor numerico.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' es un campo requerido.\n'; }
  } if (errors) alert('Los siguentes errores ocurrieron:\n'+errors);
  document.MM_returnValue = (errors == '');
}
function Cargar(){
      $.ajax({ 
                    type : "POST", 
                    url : "requisicion_alta1.php", 
                    data : $("#buscarcliente"), 
                    
                    success: function(){ 
                        
                        //alert($("#buscarcliente").val());
						$("#form1").submit(); 
                    } 
					
                }) ; 
				//document.getElementById('form1').submit(); 
 
}
 function findPosX2()
  {
	var obj = document.getElementById('buscarcliente');
    var curleft = 0;
    if(obj.offsetParent)
        while(1)
        {
          curleft += obj.offsetLeft;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
		curleft = curleft + 1;

	  var obj = document.getElementById('buscarcliente');
    var curtop = 0;
    if(obj.offsetParent)
        while(1)
        {
          curtop += obj.offsetTop;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
		
    //return curtop;
	//************Cambiar valores DIV
	var divLeft = parseInt(curleft) - 1;
	document.getElementById('search_cliente').style.left = divLeft;
	var divTop = parseInt(curtop) + 23;
	document.getElementById('search_cliente').style.top = divTop;	
  }
//-->
</script>

</head>
<? 
		 
		   
			$idcli = explode(" - ",$_POST[buscarcliente]); 
			 $consulta2 ="select c.* from clientes as c where c.id_cliente='".$idcli[0]."'";
			$resultado2 =  mysql_query($consulta2,$conexion);
			$e = mysql_fetch_array($resultado2);
			
			
		  ?>
<body onLoad="oculta_boton();findPosX2();findPosX3();">
<form enctype="multipart/form-data" action="" method="post" name="form1" id="form1">
  <table width="878" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <td height="38" bgcolor="#FFFFFF" class="Estilo5"><strong>:: NUEVA REQUISICI&OacuteN; <span class="Estilo7 Estilo11">
        <input name="id_proveedor" type="hidden" id="id_proveedor" value="<? echo $_POST[id_proveedor]; ?>">
</span></strong></td>
    </tr>
    
    <tr>
    
     <td height="38" bgcolor="#FFFFFF" class="Estilo5">
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000"> 
      <tr>
      	<? $linea_c = "select id_linea, linea from productos_lineas where estado='1'";
			$linea_b = mysql_query($linea_c,$conexion);?>
      	<td align="center">
        	<div><span class="Estilo15">LINEA:</span></div>
        </td>
        <td colspan="3">
        	<select name="id_linea" class="text1" id="id_linea" onChange="cambio_linea()">
                  <? while($linea_a = mysql_fetch_array($linea_b)) 
				  { ?>
                    <option value="<? echo $linea_a[id_linea];?>" <? if($_POST[id_linea] == $linea_a[id_linea]) {echo "selected";}?>><? echo $linea_a[linea];?></option>
                    <? } ?>
                    
                  </select>
      	</td>
    </tr>
      <tr>
      <td colspan="4">
      <div align="right"><span class="Estilo15">FECHA <? echo "".$fechahoy; ?></span></div>
      <div>
       <span class="Estilo15"> &nbsp;&nbsp;&nbsp;&nbsp;CLIENTE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             <input readonly name="Bandera" type="hidden" class="text1" id="Bandera" value="1" size="5">
                <input id="buscarcliente" name="buscarcliente" alt="Criterio de Busqueda" onKeyUp="searchCliente();" autocomplete="off" size="50" maxlength="55" onChange="Cargar();" value="<? echo $_POST[buscarcliente]; ?>"> Buscador de clientes, si no exite agregar los datos manualmente.
               </span>
            <div id="search_cliente" name="search_cliente" > </div> 
      </div>
      </td>
      </tr>
   <tr>
      <td align="center">
    <span class="Estilo15"> EMPRESA 
    </span>
    </td>
    <td>
    <input type="text" size="40" name="razonsocial" id="razonsocial" value="<? if($_POST[razonsocial]){ echo $_POST[razonsocial];} else {echo $e[razonsocial];} ?>">
    </td>
    <td align="center">
    <span class="Estilo15"> RFC 
    </span>
    </td>
    <td><input type="text" size="30" name="rfc" id="rfc" value="<? if($_POST[rfc]){ echo $_POST[rfc];} else {echo $e[rfc];} ?>">
     </td>
     </tr>
     <tr>
         <td align="center">
        <span class="Estilo15">  CALLE 
        </span>
        </td>
    	<td><input type="text" size="30" name="calle" id="calle" value="<? if($_POST[calle]){ echo $_POST[calle]; }else { echo $e[calle]; } ?>">
        </td>
         <td align="center">
        <span class="Estilo15">  NO EXT &nbsp;&nbsp;&nbsp;
        </span><input type="text" size="10" name="no_ext" id="no_ext" value="<? if($_POST[no_ext]) { echo $_POST[no_ext];} else { echo $e[no_ext]; } ?>">
        </td>
    	<td align="center">
        <span class="Estilo15">  NO INT &nbsp;&nbsp;&nbsp;
        </span>
        <input type="text" size="10" name="no_int" id="no_int" value="<? if($_POST[no_int]) { echo $_POST[no_int];} else { echo $e[no_int]; } ?>">
        </td>
        </tr>
        <tr>
         <td align="center">
        <span class="Estilo15"> COLONIA  
        </span></td>
        <td>
        <input type="text" size="25" name="colonia" id="colonia" value="<? if($_POST[colonia]) { echo $_POST[colonia];} else {echo $e[colonia]; }?>">
        </td>
    	<td align="center">  
        <span class="Estilo15"> CP  
        </span>
        </td>
        <td>
        <input type="text" size="5" name="cp" id="cp" value="<? if($_POST[cp]) { echo $_POST[cp];} else {echo $e[cp]; }?>">
  		</td>
         </tr>
        <tr>
        <td align="center">
        <span class="Estilo15"> CIUDAD  
        </span>
        </td>
    	<td><input type="text" size="30" name="ciudad" id="ciudad" value="<? if($_POST[ciudad]) { echo $_POST[ciudad];} else {echo $e[cd]; }?>">
    
         </td>
    
         <td align="center">
          <span class="Estilo15"> ESTADO
        </span>
        </td>
        
    	<td>
         <? 
      
    	  $consulta3 = "Select * FROM Estados where status = 1 order by id_estado";
          $resultado3 = mysql_query($consulta3,$conexion);
?>
			<select name="dom_estado" class="text1" id="dom_estado" >
            	<option value="0" default>--Selecciona un Estado--</option>
			<? while($arreglo3 = mysql_fetch_array($resultado3))
			{
				  ?>
                      <option value="<? echo $arreglo3[id_estado]; ?>" <? if($_POST[dom_estado]==$arreglo3[id_estado]){ echo "selected"; } else if($e[dom_estado]==$arreglo3[id_estado]){ echo "selected"; }?>>
					  <? echo $arreglo3[estados]; ?></option>
				  <?
				  	}
				  ?>
                    </select>
        
        </td>
         </tr>
        <tr>
        <td align="center">
        <span class="Estilo15"> E-MAIL 
        </span>
        </td>
    	<td>
        <input type="text" size="30" name="email" id="email" value="<? if($_POST[email]) { echo $_POST[email];} else { echo $e[email];} ?>">
        </td>  
        <td align="center">
        <span class="Estilo15">  EJECUTIVO 
        </span>
        </td>
    	<td>
        <div>        
        <input type="text" size="30" name="ejecutivo" id="ejecutivo" value="<? if($_POST[ejecutivo]) { echo $_POST[ejecutivo];} else { echo $_POST[ejecutivo]; }?>" onKeyUp="searchVendedor();" autocomplete="off"  class="text1">    
         <div id="search_vendedor" name="search_vendedor" > </div> 
     </div>
        </td>
    </tr>
     <tr>
        <td align="center">
        <span class="Estilo15">TELEFONO
        </span>
        </td>
    	<td>
        <input type="text" size="30" name="telefono" id="telefono" value="<? if($_POST[telefono]) { echo $_POST[telefono];} else { echo $e[telefono];}  ?>">
        </td>  
        </tr>
    </table>
</td>
   
    </tr>
    
    <tr>
      <td width="903" height="127" valign="top"><div align="center">
        <table width="100%" border="0" cellpadding="1" cellspacing="1" bordercolor="#000000">
          <tr bgcolor="#FFFFFF" class="Estilo7">
            <td width="21" height="25"><div align="center" class="Estilo15"><strong>PRODUCTO/<br>IMPORTACION</strong></div></td>
            <td width="179"><div align="center" class="Estilo15">DESCRIPCION</div></td>
            <td width="110"><div align="center" class="Estilo15">IMAGEN</div></td>
            <td width="69"><div align="center" class="Estilo15">PRECIO UNIT</div></td>
            <td width="50"><div align="center" class="Estilo15">CANT</div></td>
            <td width="79"><div align="center" class="Estilo15">IMPORTE</div></td>
            <td width="64">&nbsp;</td>
          </tr>
		  <?
		  $total = 0;
		  $piezas = 0;
		  for($i=1;$i<=$_SESSION[entrada][contador];$i++)
		  	{
				$subprod="select * from subproductos_stock where id_sub_prod='".$_SESSION[entrada][$i][id_sub_prod]."' ";
				$subprod1=mysql_query($subprod,$conexion);
				$subprod2=mysql_fetch_array($subprod1);
		  ?>
          <tr bgcolor="#FFFFFF" class="Estilo7">
            <td height="35"><div align="center" class="Estilo5 Estilo17"><? echo $_SESSION[entrada][$i][id_sub_prod]."/".$subprod2[rowid_det]; ?></div></td>
            <td><div align="left" class="Estilo16">
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td scope="col"><div align="left" class="Estilo5">
                    <div align="center" class="Estilo17">
					<? 
					
					
					echo $_SESSION[entrada][$i][producto]."<br>".$_SESSION[entrada][$i][modelo]."&nbsp;".$_SESSION[entrada][$i][watts]."&nbsp;".$_SESSION[entrada][$i][voltaje]."&nbsp;".$_SESSION[entrada][$i][grados_k]."&nbsp;".$_SESSION[entrada][$i][color]."&nbsp;".$_SESSION[entrada][$i][base];
					
					?></div>
                  </div>                    </td>
                </tr></table>
              <div align="center"></div><table width="98%"  border="0" cellspacing="0" cellpadding="0">
              </table>
              </div></td>              
            <td><div align="right" class="Estilo16"> <img src="catalogo/<? echo $_SESSION[entrada][$i][imagen]; ?>" width="90" height="90" />&nbsp;&nbsp;</div></td>
            <td><div align="center" class="Estilo16">$ <? echo number_format($_SESSION[entrada][$i][precio] ,2,".",","); ?>&nbsp;&nbsp;</div></td>
             <td><div align="center" class="Estilo16"> <? echo $_SESSION[entrada][$i][cantidad]; ?>&nbsp;&nbsp;</div></td>
            <td><div align="center" class="Estilo16">$ <? echo number_format($_SESSION[entrada][$i][precio_imp]  ,2,".",","); ?>&nbsp;&nbsp;</div></td> 
            <input name="id_producto" type="hidden" id="id_producto" value="<? echo $_SESSION[entrada][$i][id_producto]; ?>">            
           <!-- <input name="id_producto" type="hidden" id="id_producto" value="<? //echo $_POST[Incluye_imp]=$_SESSION[entrada][$i][incluye_imp]; ?>">-->                         
            <td><div align="center" class="Estilo17"><a href="#" onClick="MM_openBrWindow('requisicion_detalle_eliminar2.php?id=<? echo $i; ?>','','width=50,height=50')"><img src="../../img/eliminar.png" alt="Eliminar producto" width="24" height="24" border="0"></a></div></td>
          </tr>
		  <?		   
		  	$total = $total + ($_SESSION[entrada][$i][precio_imp]);
			$piezas = $piezas + ($_SESSION[entrada][$i][cantidad]);
		  	}
		if($total != 0 or $piezas != 0)
			{
		  ?>
          <tr bgcolor="#FFFFFF" class="Estilo7">
            <td height="46" colspan="3">
            <div align="right" class="Estilo11 Estilo17">
            <strong>TOTAL MXN:&nbsp;&nbsp;</strong> </div>              
            <div align="center" class="Estilo17"></div>              
            <div align="center" class="Estilo11 Estilo17">
              <div align="right"><strong>TOTAL USD:&nbsp;&nbsp;</strong></div>
            </div></td>
            <td colspan="3">
             <div align="center" class="Estilo17">
                <span class="Estilo5">
                    <span class="Estilo5">
                        <span class="Estilo11">
                            <span class="Estilo14">
                            <label id="lblTotal">$<? echo number_format($total*$_SESSION[venta][tipo_cambio],2,".",",");?></label>
                            </span>
                        </span>
                    </span>
                </span>
                 </div>
            <div align="center" class="Estilo17">
            <span class="Estilo5">
                <span class="Estilo10">
                    <span class="Estilo10">
                        <span class="Estilo5">
                            <span class="Estilo5">
                                <span class="Estilo11">
                                    <span class="Estilo14">
                                    <label id="lblTotal">$<? echo number_format($total,2,".",",");?></label>
                                    <input name="Total" type="hidden" id="Total" value="<? echo $total; ?>">
                                    <input name="Total_u" type="hidden" id="Total_u" value="<? echo $total; ?>">
                                    </span>
                                </span>
                            </span>
                        </span>
                    </span>
                </span>
            </span></div></td>
            
            </tr>
          <tr valign="top" class="Estilo7">
          <td></td>
            <td height="48" colspan="3">
            
            <div align="right" class="Estilo18">
              <div align="left">
                
                <p class="Estilo7">&nbsp;&nbsp;&nbsp;&nbsp;# TOTAL UNIDADES:&nbsp;<br>
                      &nbsp;&nbsp;&nbsp;
 &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<input name="unidades" type="text" id="unidades" size="5" style="font-family: tahoma; font-size: 14px; background-color:#E4EBD8; cursor:pointer;" value="<? echo $_SESSION[entrada][contador]; ?>">
                      <br>
                    </p>
                    
                     <p class="Estilo7">&nbsp;&nbsp;&nbsp;&nbsp;NOTAS ESPECIALES:&nbsp;<br>
                      &nbsp;&nbsp;&nbsp;
 &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<textarea name="notas" cols="45" rows="4" wrap="VIRTUAL" class="text1" id="notas"></textarea>
                      <br>
                    </p>
                     <p class="Estilo7">&nbsp;&nbsp;<br>
                    </p>
</div>
            </div>              </td>
            <td colspan="2" rowspan="3">             
            <div align="center">
              <p class="Estilo17">
                <input name="Finalizar" type="submit" class="boton1" id="Finalizar2" value="  Finalizar Requisicion " onClick="MM_validateForm('email','','isEmail');return document.MM_returnValue">
                  </p>
              </div></td>
            </tr>
          <tr valign="top" class="Estilo7">
          <td></td>
                <td valign="top" class="Estilo71" ><span style="display" class="Estilo5">TIPO DE CAMBIO<br />$USD
                  
              </span>  <input name="dolar" type="text" class="text1" id="dolar" size="10" value="<? if($_POST[dolar]){ echo $_POST[dolar];} else { echo $a_dolar[valor];}?>"  />        <? if($fecha1 != $fecha_tipo_cambio && $fecha_tipo_cambio != ""){ echo" La fecha del tipo de cambio es: ".$fecha_tipo_cambio." es decir, no es de hoy"; } ?></td>
            
            </tr>
		  <?
		  	}
		?>
        </table>
        <tr>
        <td>       
          <table width="100%" border="0" cellpadding="1" cellspacing="1" bordercolor="#000000">              
              <tr>  
                 <td width="15%" colspan="2"><div align="center" class="Estilo15">PRODUCTO</div></td>
                 <td width="15%" colspan="2"><div align="center" class="Estilo15">MODELO</div></td>
                 <td width="10%" colspan="2"><div align="center" class="Estilo15">WATTS</div></td>
                 <td width="10%" colspan="2"><div align="center" class="Estilo15">KELVINS</div></td>
                 <td width="15%"><div align="center" class="Estilo15">CANTIDAD</div></td>
                  
                 <td width="15%"><div align="center" class="Estilo15">COSTO USD</div></td> 
                <td width="15%"><div align="center" class="Estilo15">OBSERVACIONES</div></td>
                 <td width="15%"><div align="center" class="Estilo15">IMAGEN</div></td>
              </tr> 
              <tr>                                           
                <td width="15%" class="Estilo18" align="center" colspan="2"><div>
               <input id="producto" name="producto"  autocomplete="off" size="25"  value="<? if($_POST[producto]) echo $_POST[producto];?>"  onBlur="oculta_boton()">
                </span></div>
           <!-- <div id="search_suggest"> </div>--></td>               
            <br />                               
                  <td width="15%" class="Estilo18" align="center" colspan="2"><div>
               <input id="modelo" name="modelo" autocomplete="off" size="20"  value="<? if($_POST[modelo]) echo $_POST[modelo];?>"  onBlur="oculta_boton()">
                </span></div>
                </td> 
                <td width="10%" class="Estilo18" align="center" colspan="2"><div>
               <input id="watts" name="watts" autocomplete="off" size="15"  value="<? if($_POST[watts]) echo $_POST[watts];?>"  onBlur="oculta_boton()">
                </span></div>
                </td> 
                <td width="10%" class="Estilo18" align="center" colspan="2"><div>
               <input id="kelvins" name="kelvins"  autocomplete="off" size="15"  value="<? if($_POST[kelvins]) echo $_POST[kelvins];?>"  onBlur="oculta_boton()">
                </span></div>
                </td>     
                <td width="15%" class="Estilo22" align="center"><div >
                 <input name="Cantidad" type="text" class="text1" id="Cantidad" size="2" dir="rtl" value="<? echo $_POST[Cantidad];?>"></div>                
                </td> 
              
                
                   <td width="15%" class="Estilo22" align="center"><div id="muestra3" style="display">
                   <input name="Precio" type="text" class="text1" id="Precio" value="<? echo $_POST[Precio];?>" size="10" dir="rtl" ></div>
                   </td> 
                   <td width="15%">
                   <textarea name="Observa" cols="20" rows="2" wrap="VIRTUAL" class="text1" id="Observa"><? echo $a_clientes[observaciones]; ?></textarea>
                   </td>  
                   <td ><input name="file" type="file" class="boton1" id="file"></td>
                
                <input readonly name="sub_total" type="hidden" class="text1" id="sub_total" value="<? echo $suma;?>" size="5">
               
                <td width="2%" class="Estilo22" align="center">
                  <div align="center" id="muestra5" style="display"><a href="#" onClick="javaScript:form1.Agregar.value = 1; form1.submit();"><img src="../../img/agregar.png" width="24" height="24" border="0"></a>
                <input name="Agregar" type="hidden" id="Agregar" value="0">
              </div>              
              <div align="center"><a href="#" onClick="javaScript:form1.submit();"></a></div>
               </td>               
               </tr> 
                               
               <tr>
                  
     <tr valign="middle">
      <td height="57">        
      <div align="left"><span class="Estilo5"><strong><span class="Estilo7 Estilo11">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="Cancelar" onClick="form1.cancelar2.value=1; form1.submit();" type="button" class="boton1" id="Cancelar" value="  Cancelar esta Requisicion  ">
                <input name="cancelar2" type="hidden" id="cancelar2" value="0">
        </span></strong></span>&nbsp;&nbsp;&nbsp; &nbsp;</div>
      <div align="right"></div></td>
    </tr>                                     
          </table>
         </td>
        </tr> 
  </table>
  </form>
</body>
</html>
                