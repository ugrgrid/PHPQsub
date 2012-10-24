<?php
/*
 * sesion_web.php
 * 
 * Copyright (C) 2010, 2012 Universidad de Granada
 * Authors: Leire López, Rafael Arco
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

session_start();
require('params.php');
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
?>

<script type="text/javascript" language="javascript">

function check_all_input(){
    var array_input = new Array(9);
    array_input[0] = "name_job";
    array_input[1] = "holdjid";
    array_input[2] = "standar_o";
    array_input[3] = "standar_e";
    array_input[4] = "input_file";
    array_input[5] = "resources";
    array_input[6] = "tasks";
    array_input[7] = "enviroment";
    array_input[8] = "send_mail";
    var array_tipo_input = new Array(9);
    array_tipo_input[0] = "A";
    array_tipo_input[1] = "N";
    array_tipo_input[2] = "A";
    array_tipo_input[3] = "A";
    array_tipo_input[4] = "A";
    array_tipo_input[5] = "A";
    array_tipo_input[6] = "N";
    array_tipo_input[7] = "A";
    array_tipo_input[8] = "E";
    var array_campo_input = new Array(9);
    array_campo_input[0] = "Nombre del trabajo";
    array_campo_input[1] = "Trabajo anterior";
    array_campo_input[2] = "Salida estandar";
    array_campo_input[3] = "Error estandar";
    array_campo_input[4] = "Fichero de entrada";
    array_campo_input[5] = "Recursos adicionales";
    array_campo_input[6] = "Tareas";
    array_campo_input[7] = "Variables de entornos";
    array_campo_input[8] = "Enviar e-mail";
    var existe = true;
    for( var i=0; i< array_input.length; i++){
	if(document.getElementById('check_'+array_input[i]).checked){
	    if(array_tipo_input[i]=='A'){
		existe = alfanumerico(array_campo_input[i],array_input[i]);
	    }else if(array_tipo_input[i]=='N'){
		existe = numerico(array_campo_input[i],array_input[i]);
	    }else if(array_tipo_input[i]=='E'){
		existe = email(array_campo_input[i],array_input[i]);
	    }
	}
	if(!existe){
	    existe = false;
	    break;
	}
    }
    return existe;
}

function alfanumerico(campo, id_input){
    var caracteres="abcdefghijklmnñopqrstuvwxyz0123456789._-,=";
    var texto = document.getElementById(id_input).value;
    texto = texto.toLowerCase();
    var existe = true;
    if(texto != ''){
	for(var i=0; i<texto.length; i++){
	    if (caracteres.indexOf(texto.charAt(i),0)==-1){
		existe = false;
		break;
	    }
	}
    }else existe = false;
    if(!existe){
	alert("Recuerde que el campo "+campo+" debe ser de tipo alfanumérico");
	document.getElementById(id_input).focus();
    }
    return existe;
}

function numerico(campo, id_input){
    var caracteres="0123456789";
    var texto = document.getElementById(id_input).value;
    var existe = true;
    if(texto != ''){
	for(var i=0; i<texto.length; i++){
            if (caracteres.indexOf(texto.charAt(i),0)==-1){
                existe = false;
	        break;
            }
	}
    }else existe = false;
    if(!existe){
        alert("Recuerde que el campo "+campo+" debe ser de tipo numérico");
        document.getElementById(id_input).focus();
    }
    return existe;
}

function email(campo, id_input){
    var caracteres="0123456789abcdefghijklmnñopqrstuvwxyz0123456789._@";
    var texto = document.getElementById(id_input).value;
    var existe = true;
    if(existe != ''){
	for(var i=0; i<texto.length; i++){
            if (caracteres.indexOf(texto.charAt(i),0)==-1){
	        existe = false;
        	break;
	    }
        }
    }else existe = false;
    if(!existe){
        alert("Recuerde que el campo "+campo+" debe ser un email válido");
        document.getElementById(id_input).focus();
    }
    return existe;
}

function checkTipoSubmit(){
    var value = 0;
    for( var i=0; i < document.radiobuttons.tipo_submit.length;i++){
	if(document.radiobuttons.tipo_submit[i].checked){
	    value = document.radiobuttons.tipo_submit[i].value;
	}
    }
    if(value <= 1){ // modo sencillo
	document.getElementById('complejo').style.display = 'none';
	document.getElementById('sencillo').style.display = '';
    }else{ 	// modo complejo
	document.getElementById('complejo').style.display = '';
	document.getElementById('sencillo').style.display = 'none';
    }
}

function manejar_checkbox(id_check){
    var check = document.getElementById("check_"+id_check);
    if(!check.checked){
	document.getElementById("check_start_status_mail").checked=false;
	document.getElementById("check_end_status_mail").checked=false;
	document.getElementById("check_abort_status_mail").checked=false;
	document.getElementById("check_suspend_status_mail").checked=false;
	document.getElementById("label_all_status_mail").innerHTML= "Todos";
    }else {
	document.getElementById("check_start_status_mail").checked=true;
        document.getElementById("check_end_status_mail").checked=true;
        document.getElementById("check_abort_status_mail").checked=true;
        document.getElementById("check_suspend_status_mail").checked=true;
	document.getElementById("label_all_status_mail").innerHTML= "Ninguno";
    }
}

function manejar_input(id_input){
    var input = document.getElementById(id_input);
    var check = document.getElementById("check_"+id_input);
    var estado_v = ''; // para estilo visibility
    var estado_d = ''; // para estilo display
    if(!check.checked){
	input.style.visibility = 'hidden';
	input.readonly = true;
	estado_v = 'hidden';
	estado_d = 'none';
    }
    else{
	input.style.visibility = 'visible';
	input.readonly = false;
	estado_v = 'visible';
	estado_d = 'block';
    }
    if(id_input=="input_file"){
	document.getElementById("label_"+id_input).style.visibility=estado_v;
    }else if (id_input=="send_mail"){
	document.getElementById("div_"+id_input).style.display=estado_d;
    }
}

function startUpload(){
    document.getElementById('f1_upload_process').style.display = 'block';
    return true;
}

function stopUpload(error,mensaje){
    if (error != 0) {
        document.getElementById('result').innerHTML ='<div class="error">' + mensaje + '</div>';
    }
    else {
        document.getElementById('result').innerHTML ='<div class="noerror">' + mensaje + '</div>';
    }

    document.getElementById('f1_upload_process').style.display = 'none';
    return true;
}


function checkProcesadores(){
    var cola = document.getElementById('cola').options[document.getElementById('cola').selectedIndex].value;
    var proc = document.getElementById('proc').options[document.getElementById('proc').selectedIndex].value;
    if(cola.search("bigmem") != -1 ){ //son colas de tipo bigmem, proc debe ser multiplo de 16
	resto = proc %16;
	if(resto != 0) 	alert("Para ejecutar en la cola "+cola+" debe indicar un numero de procesadores multiplo de 16");
    }
}



var numero = 0; 

evento = function (evt) { 
    return (!evt) ? event : evt;
}

addCampo = function () { 
    //Creamos un nuevo div para que contenga el nuevo campo
    nDiv = document.createElement('div');
    nDiv.className = 'archivo';
    nDiv.id = 'file' + (++numero);
    nCampo = document.createElement('input');
    nCampo.name = 'archivos[]';
    nCampo.type = 'file';
    a = document.createElement('a');
    a.name = nDiv.id;
    a.onclick = elimCamp;
    a.className= 'enlace_form_eliminar';
    a.innerHTML = 'Eliminar';
    nDiv.appendChild(a);
    nDiv.appendChild(nCampo);
    container = document.getElementById('adjuntos');
    container.appendChild(nDiv);
}

elimCamp = function (evt){
    evt = evento(evt);
    nCampo = rObj(evt);
    div = document.getElementById(nCampo.name);
    div.parentNode.removeChild(div);
}

rObj = function (evt) { 
    return evt.srcElement ?  evt.srcElement : evt.target;
}


</script>

<?php
function mostrar_dir_as_ul($ruta){
  $directorio = scandir($directorio_raiz.$ruta);
  $salida = "<ul>";
  foreach ($directorio as $subdir){
    if($subdir != "." && $subdir != ".."){
      if(is_dir($directorio_raiz.$ruta."/".$subdir)){
	$salida=$salida."<li>Directorio:".$subdir;
	$salida=$salida.mostrar_dir_as_ul($ruta."/".$subdir);	
	$salida=$salida."</li>";
      }else{
	$dir_escape = str_replace(".","__",$subdir);
	$dir_escape = str_replace("-","__",$dir_escape);	
	echo "<form name='descargar_fichero_".$dir_escape."' action='descargas.php' method='POST'>";
	echo "<input type='hidden' value='".$ruta."' name='ruta' id='ruta' />";
	echo "<input type='hidden' value='".$subdir."' name='doc' id='doc' />";
	echo "</form>";
	$salida =$salida."<li><a href='" . $directorio_descargas. $ruta."/".$subdir."'>".$subdir."</a> <a onclick='javascript:document.descargar_fichero_".$dir_escape.".submit();' >Descargar aqui</a></li>";
      }
    }
  }
  $salida =$salida."</ul>";
  return $salida;
}

function formulario_select_cola(){
echo <<<EOD
<label for="cola">Cola:</label>
<select name="cola" id="cola" onchange="checkProcesadores();">
        <option value="12H">12H</option>
        <option value="24H">24H</option>
        <option value="72H">72H</option>
        <option value="24Hbigmem">24Hbigmem</option>
        <option value="72Hbigmem">72Hbigmem</option>
        <option value="120Hbigmem">120Hbigmem</option>
        <option value="visitante">Visitante</option>
</select>
EOD;
}

function formulario_select_procesadores(){
  echo '<label for="proc">N&ordm; procesadores:</label><select name="proc" id="proc">';
  for($i=4;$i <= 256 ; $i+=4){
    echo "<option value=".$i.">".$i."</option>";
  }
  echo "</select>";
}

function formulario_input_fichero_entrada(){
  echo '<label for="myfile">Archivo de entrada:</label><input name="myfile" type="file" />';
}

function formulario_input_ficheros_adicionales(){
  echo <<<EOD
<p>Si su trabajo necesita ficheros adicionales, utilice el siguiente formulario para a&ntilde;adirlos. Recuerde que estos ficheros estar&aacute;n en el mismo directorio que el fichero de entrada.</p>
<div class="pestania">FICHEROS ADICIONALES</div>
<fieldset>
        <!-- Esta div contendrá todos los campos file que creemos -->
    <div id="adjuntos">
        <label for="archivos[]">Otros ficheros</label>
        <input type="file" name="archivos[]" />
   </div>
   <a class="enlace_form" onClick="addCampo()">Subir otro archivo</a>
</fieldset>
EOD;
}


function formulario_inicio_form($usuario,$tipo){

  echo '<form action="upload_sesion_web.php" id="formcontacto" method="post" enctype="multipart/form-data" target="upload_target" ';
  if($tipo==1) echo ' onsubmit="if(check_all_input()){ startUpload();}else return false;">';
  else  echo ' onsubmit="startUpload();">';

  echo '<input type="hidden" id="usuario" name="usuario" value="'.$usuario.'"  class="oculto"  />';
}

function formulario_fin_form(){
  echo <<<EOD
<div>
   <input type="submit" class="botonimagen2" src="images/transp.gif" alt="Subir" name="subir" id="subir" value="ENVIAR Y SUBIR" />
</div>
  <p>&nbsp;<input type="reset" name="limpiar" value="LIMPIAR DATOS" class="boton_form" /></p>
  <p>&nbsp;<input type="button" value="RECARGAR P&Aacute;GINA" onClick="location.reload();" class="boton_form"/></p>
</form>
EOD;
}

function formulario_trabajo_simple($usuario){
  formulario_inicio_form($usuario,0);
  echo <<<EOD
<div class="pestania">DATOS BASICOS</div><fieldset>

<label for="aplicacion">Aplicaci&oacute;n</label>
<select name="aplicacion" id="aplicacion">
        <option value="qdock">DOCK 6</option>
        <option value="qg03">Gaussian 03</option>
        <option value="qnamd">NAMD</option>
        <option value="qnamd-2.7b1">NAMD 2.7b1</option>
        <option value="qnwchem">NwChem 5.1</option>
        <option value="qnwchem">NwChem 5.1.1</option>
        <option value="qnwchem-big">NwChem 5.1 (+)</option>
        <option value="qsander">Sander (Amber 10)</option>
        <option value="qsiesta">Siesta 2.0.1</option>
        <option value="qsiesta-2.0.2">Siesta 2.0.2</option>
</select>
EOD;

formulario_select_procesadores();
formulario_select_cola();
formulario_input_fichero_entrada();
echo "</fieldset>";
formulario_input_ficheros_adicionales();
formulario_fin_form();
}

function formulario_trabajo_complejo($usuario){
  formulario_inicio_form($usuario,1);

  echo '<p>Para enviar un trabajo a trav&eacute;s de la web rellene el siguiente formulario con los datos que crea convenientes y complete el script que va a enviar.</p>';

  echo '<div class="pestania">DATOS BASICOS</div><fieldset>';
  formulario_select_procesadores();
  formulario_select_cola();
  formulario_input_fichero_entrada();

  echo <<<EOD
</fieldset>
<p>Si desea que se a&ntilde;adir alguna de las opciones que a continuaci&oacute;n se muestran, marquela y rellene el dato. Para obtener m&aacute;s informaci&oacute;n sobre estos par&aacute;metros consulte la secci&oacute;n de <a href='index.php?page=sge'>Sun Grid Engine 6.2</a>.</p>
<div class="pestania">OTROS PAR&Aacute;METROS</div>

<fieldset>
<div>	<input type='checkbox' id ='check_name_job' class='tres_columnas' onclick='manejar_input("name_job");'/>
        <span title='Permite renombrar el trabajo a un nombre m&aacute;s representativo que el dado por defecto (nombre del script que se envia)'><label id='label_name_job' class='tres_columnas'>Nombre del trabajo</label></span>
        <input type='text' name='name_job' id='name_job' style='visibility:hidden;' onBlur='alfanumerico("Nombre del trabajo","name_job");' /></div>

	<input type='checkbox' id ='check_holdjid' class='tres_columnas' onclick='manejar_input("holdjid");'/>
        <span title='Bloquea el trabajo actual hasta que no se haya completado el trabajo indicado. Debe ser el jobid de otro trabajo, un n&uacute;mero.'><label class='tres_columnas' >Trabajo anterior</label></span>
        <input type='text' name='holdjid' id='holdjid' style='visibility:hidden;' onBlur='numerico("Trabajo anterior","holdjid");' />

	<input type='checkbox' id ='check_standar_o' class='tres_columnas' onclick='manejar_input("standar_o");'/>
        <span title='Redirige la salida estandar a un fichero distinto del fichero por defecto.'><label class='tres_columnas' >Salida Estandar</label></span>
        <input type='text' name='standar_o' id='standar_o' style='visibility:hidden;' onBlur='alfanumerico("Salida Estandar","standar_o");' />

	<input type='checkbox' id ='check_standar_e' class='tres_columnas' onclick='manejar_input("standar_e");'/>
	<span title='Redirige el error estandar a un fichero distinto del fichero por defecto.'><label class='tres_columnas' >Error Estandar</label></span>
	<input type='text' name='standar_e' id='standar_e' style='visibility:hidden;' onBlur='alfanumerico("Error Estandar","standar_e");' /> 
	
	<input type='checkbox' id ='check_merge_output' class='tres_columnas' onclick='manejar_input("merge_output");'/>
        <span title='Incluye el error estandar en el mismo fichero que la salida estandar del trabajo'><label class='tres_columnas' >Fusionar salidas </label></span>
        <input type='text' name='merge_output' id='merge_output' style='visibility:hidden;' value='Activado' readonly />

	<input type='checkbox' id ='check_input_file' class='tres_columnas' onclick='manejar_input("input_file");'/>
       	<span title='Define el fichero que va a utilizar como flujo de entrada la aplicaci&oacute;n que se ejecuta en el trabajo'><label class='tres_columnas' >Fichero Entrada</label>
        <input type='text' name='input_file' id='input_file' style='visibility:hidden;' onBlur='alfanumerico("Fichero Entrada","input_file");' /> 
	<label class='tres_columnas nota'  style='visibility:hidden;' id='label_input_file'>Deber&aacute; a&ntilde;adirlo en la secci&oacute;n de ficheros adicionales</label>

	<input type='checkbox' id ='check_resources' class='tres_columnas' onclick='manejar_input("resources");'/>
        <span title='Indica la m&aacute;xima cantidad de recursos que se van a utilizar durante la ejecuci&oacute;n del trabajo. Separados por comas.'><label class='tres_columnas' >Recursos adicionales</label></span>
        <input type='text' name='resources' id='resources' style='visibility:hidden;' onBlur='alfanumerico("Recursos adicionales","resources");' />

	<input type='checkbox' id ='check_reservation' class='tres_columnas' onclick='manejar_input("reservation");'/>
        <span title='Reserva los recursos que se liberan para el trabajo, siempre y cuando no haya otros trabajos que soliciten menos recursos. SOLO PARA TRABAJOS MASIVAMENTE PARALELOS (m&aacute;s de 128 procesadores)'><label class='tres_columnas' >Reserva recursos</label></span>
        <input type='text' name='reservation' id='reservation' style='visibility:hidden;' value='Activada' readonly />

	<input type='checkbox' id ='check_tasks' class='tres_columnas' onclick='manejar_input("tasks");'/>
        <span title='Envia tantas ejecuciones del trabajo como se indiquen'><label class='tres_columnas' >N&uacute;mero de tareas</label></span>
        <input type='text' name='tasks' id='tasks' style='visibility:hidden;' onBlur='numerico("Número de tareas","tasks");' />

	<input type='checkbox' id ='check_enviroment' class='tres_columnas' onclick='manejar_input("enviroment");'/>
        <span title='Define las variables de entorno que van a ser necesarias para la aplicaci&oacute;n'><label class='tres_columnas' >Variables de entorno</label></span>
        <textarea name='enviroment' id='enviroment' style='visibility:hidden;' onBlur='alfanumerico("Variables de entorno","enviroment");' ></textarea>
	
	<input type='checkbox' id='check_shell' class='tres_columnas' onclick='manejar_input("shell");' />
	<span title='Define el tipo de shell que va a utilizarse en el script que se envia. Por defecto ser&aacute; Bash'><label class='tres_columnas'>Tipo de shell</label></span>
	<select id='shell' name='shell' style='visibility:hidden;'>	
		<option value='/bin/bash'>Bash</option>
		<option value='/bin/sh'>Sh</option>	
		<option value='/usr/bin/tcsh'>Tcsh</option>
		<option value='/usr/bin/ksh'>Ksh</option>
		<option value='/usr/bin/csh'>Csh</option>
	</select>	
	<input type='checkbox' id ='check_send_mail' class='tres_columnas' onclick='manejar_input("send_mail");'/>
        <span title='Establece notificaciones de e-mail seg&uacute;n es estado del trabajo'><label class='tres_columnas' >Enviar e-mail</label></span>
        <input type='text' name='send_mail' id='send_mail' style='visibility:hidden;' onBlur='email("Enviar e-mail","send_mail");' />
	<div id='div_send_mail' style='display:none;'> 
		<label for='check_start_status_mail' class='to_checkbox' >Al iniciar</label>
			<input type='checkbox' name='check_start_status_mail' id='check_start_status_mail'/>
		<label for='check_end_status_mail' class='to_checkbox'>Al finalizar</label>
        	        <input type='checkbox' name='check_end_status_mail' id='check_end_status_mail' />
		<label for='check_abort_status_mail' class='to_checkbox'>Al abortarse</label>
        	        <input type='checkbox' name='check_abort_status_mail' id='check_abort_status_mail' />
		<label for='check_suspend_status_mail' class='to_checkbox'>Al suspenderse</label>
        	        <input type='checkbox' name='check_suspend_status_mail' id='check_suspend_status_mail' />
		<label for='check_all_status_mail' id='label_all_status_mail' class='to_checkbox' >Todos</label>
			<input type='checkbox' name='check_all_status_mail' id='check_all_status_mail' onclick='manejar_checkbox("all_status_mail");'  />
	</div>

</fieldset>

<p>Defina a continuaci&oacute;n cu&aacute;l va a ser el proceso que va a enviar, incluyendo <em>mpirun</em> para enviar el trabajo paralelo. Recuerde que para utilizar el n&uacute;mero de procesadores indicado antes puede usar la variable de SGE &#36;NSLOTS.</p>
<p>Si tiene dudas de c&oacute;mo enviar un trabajo con una aplicaci&oacute;n, consulte la secci&oacute;n de Uso en la p&aacute;gina de dicha aplicaci&oacute;n en el <a href='index.php?page=aplicaciones'>listado de aplicaciones disponibles en UGRGrid</a>.</p>
	<p>Para utilizar su propia aplicaci&oacute;n, deber&aacute; a&ntilde;adir los ficheros fuente e incluir en el script la/s instruccion/es de compilaci&oacute;n para su aplicaci&oacute;n, tras ellas incluya la instrucci&oacute;n de ejecuci&oacute;n para su aplicaci&oacute;n.</p>
	<div class='pestania'>TRABAJO</div>

	<fieldset>

		<label for='script_qsub'>Script</label><textarea id='script_qsub' name='script_qsub' rows='15'>#!/bin/sh\n...</textarea>

		</fieldset>
EOD;
formulario_input_ficheros_adicionales();
formulario_fin_form();
}


$meses = array("Enero", "Febrero", "Marzo", "Abril","Mayo", "Junio", "Julio", "Agosto", "Septiembre","Octubre","Noviembre","Diciembre");

if(isset($_SESSION['usuario'])){

  $usuario = $_SESSION['usuario']; 

  $mensaje = '';
  $ret_code = 0;
  $output = array();

  if(isset($_POST['borrar']) && !empty($_POST['borrar']) && isset($_POST['dir2del']) && !empty($_POST['dir2del']) && strlen($_POST['dir2del']) > 16 ){
    $directorio = htmlspecialchars($_POST['dir2del']);

    if(isset($_POST['enlaceFTP']) && !empty($_POST['enlaceFTP']) && file_exists($directorio) && file_exists($directorio.".ftp")
       && is_readable($directorio) && is_readable($directorio.".ftp")) {
      $tmp = posix_getpwuid(fileowner($directorio));
      $propietario = $tmp["name"];

      if($propietario == $_SESSION['usuario']) {
	exec(escapeshellcmd("rm ".$directorio.".ftp") . " && " . escapeshellcmd("sudo rm -r ".$directorio), $output, $ret_code);
      }
      else {
	$ret_code = 1;
      }
    }
    else if(file_exists($directorio) && is_readable($directorio)) {
      $tmp = posix_getpwuid(fileowner($directorio));
      $propietario = $tmp["name"];

      if($propietario == $_SESSION['usuario']) {
	exec(escapeshellcmd("sudo rm -r ".$directorio), $output, $ret_code);
      }
      else {
	$ret_code = 1;
      }
    }

    else {
      $ret_code = 1;
    }

    if($ret_code == 0) {
      $long_nombre= strlen($directorio_raiz)+1+strlen($usuario);
      $mes = substr($directorio,$long_nombre + 4,2);
      if(ereg("0[1-9]",$mes))
	$mes = substr($mes,1,1);

      $mensaje = "<div class='noerror'>Borrada Sesion Web: "
	.substr($directorio,$long_nombre + 6,2)." de "
	.$meses[$mes-1]." de "
	.substr($directorio,$long_nombre + 0,4)." a las "
	.substr($directorio, $long_nombre + 8,2).":"
	.substr($directorio, $long_nombre + 10,2).":"
	.substr($directorio, $long_nombre + 12,2)."</div>";
    }

    else {
      $mensaje = "<div class='error'>Ha ocurrido un error al borrar la sesi&oacute;n web.</div>";
    }

  }else if(isset($_POST['comprimir']) && !empty($_POST['comprimir']) && isset($_POST['dir2zip']) && !empty($_POST['dir2zip']) && strlen($_POST['dir2zip']) > 16 ){ 
    $directorio = htmlspecialchars($_POST['dir2zip']);

    if(file_exists($directorio) && is_readable($directorio)) {
      $tmp = posix_getpwuid(fileowner($directorio));
      $propietario = $tmp["name"];

      if($propietario == $_SESSION['usuario']) {
	chdir($directorio);
	exec(escapeshellcmd($zip . " " . $directorio.".zip") ." * && " . escapeshellcmd($sudo . " " . $chown . " " . $propietario . " " . $directorio . ".zip")
	     . " && " . escapeshellcmd($sudo . " " . $chmod . " 660 " . $directorio . ".zip"), $output, $ret_code);
      }
      else {
	$ret_code = 1;
      }
    }
    else {
      $ret_code = 1;
    }

    if($ret_code == 0) {
      $long_nombre= strlen($directorio_raiz)+1+strlen($usuario);
      $mes = substr($directorio,$long_nombre + 4,2);

      if(ereg("0[1-9]",$mes))
	$mes = substr($mes,1,1);

      $mensaje =  "<div class='noerror'>Comprimida Sesion Web: "
	.substr($directorio,$long_nombre + 6,2)." de "
	.$meses[$mes-1]." de "
	.substr($directorio,$long_nombre + 0,4)." a las "
	.substr($directorio, $long_nombre + 8,2).":"
	.substr($directorio, $long_nombre + 10,2).":"
	.substr($directorio, $long_nombre + 12,2)."</div>";

    }
    else {
      $mensaje = "<div class='error'>Ha ocurrido un error al comprimir el directorio.</div>";
    }

  }else if(isset($_POST['mover']) && !empty($_POST['mover']) && isset($_POST['dir2mv']) && !empty($_POST['dir2mv']) && strlen($_POST['dir2mv']) > 16 ){
    $directorio = htmlspecialchars($_POST['dir2mv']);
    $pai = $_SESSION['id_grupo'];
    $ruta = explode("/",$directorio);
    $nombre = $ruta[3];
    $long_nombre= strlen($usuario) + 14;
    $nombre_dir = substr($nombre, 0,$long_nombre);

    if(isset($_POST['enlaceFTP']) && !empty($_POST['enlaceFTP']) && file_exists($directorio) && file_exists($directorio.".ftp")
       && is_readable($directorio) && is_readable($directorio.".ftp")) {
      $tmp = posix_getpwuid(fileowner($directorio));
      $propietario = $tmp["name"];

      if($propietario == $_SESSION['usuario']) {
	exec(escapeshellcmd($rm . " " .$directorio.".ftp") . " && " . escapeshellcmd($sudo . " " . $mv . " ".$directorio.
										     " /SCRATCH/".$pai."/".$usuario."/".$nombre_dir), $output, $ret_code);
      }

      else {
	$ret_code = 1;
      }
    }

    else if(file_exists($directorio) && is_readable($directorio)) {
      $tmp = posix_getpwuid(fileowner($directorio));
      $propietario = $tmp["name"];

      if($propietario == $_SESSION['usuario']) {
	exec(escapeshellcmd($sudo . " " . $mv . " ".$directorio." /SCRATCH/".$pai."/".$usuario."/".$nombre_dir), $output, $ret_code);
      }
      else {
	$ret_code = 1;
      }
    }

    if($ret_code == 0) {
      $long_nombre= strlen($directorio_raiz)+1+strlen($usuario);
      $mes = substr($directorio,$long_nombre + 4,2);
      if(ereg("0[1-9]",$mes))
	$mes = substr($mes,1,1);
      $mensaje = "<div class='noerror'>Movida Sesion Web: "
	.substr($directorio,$long_nombre + 6,2)." de "
	.$meses[$mes-1]." de "
	.substr($directorio,$long_nombre + 0,4)." a las "
	.substr($directorio, $long_nombre + 8,2).":"
	.substr($directorio, $long_nombre + 10,2).":"
	.substr($directorio, $long_nombre + 12,2).
	"<br />Directorio: /SCRATCH/".$pai."/".$usuario."/".$nombre_dir.
	"</div>";
    }

    else {
      $mensaje = "<div class='error'>Ha ocurrido un error al mover el directorio.</div>";
    }
    
  }else if(isset($_POST['sftp']) && !empty($_POST['sftp']) && isset($_POST['dir2sftp']) && !empty($_POST['dir2sftp']) && strlen($_POST['dir2sftp']) > 16){
    $directorio = htmlspecialchars($_POST['dir2sftp']);
    $pai = $_SESSION['id_grupo'];
    $ruta = explode("/",$directorio);
    $nombre = $ruta[3];
    $long_nombre= strlen($usuario) + 14;
    $nombre_dir = substr($nombre, 0,$long_nombre);
    $ruta_sftp = "/SCRATCH/".$pai."/".$usuario."/".$nombre_dir;

    if(file_exists($directorio) && is_readable($directorio)) {
      $tmp = posix_getpwuid(fileowner($directorio));
      $propietario = $tmp["name"];

      if($propietario == $_SESSION['usuario']) {
	exec(escapeshellcmd($sudo . " " . $ln . " " . $directorio." ".$ruta_sftp) . " && " .
	     escapeshellcmd($sudo . " " . $chownh . " " . $propietario . " " . $ruta_sftp), $output, $ret_code);

	if($ret_code == 0) {
	  $fichero = $nombre.".ftp";
	  $fh = fopen($directorio_raiz.$fichero, 'w') or $ret_code = 1;

	  if($ret_code == 0) {
	    fwrite($fh, $ruta_sftp);
	    fclose($fh);
	  }
	}
      }
      else {
	$ret_code = 1;
      }
    }
    else {
      $ret_code = 1;
    }
    
    if($ret_code == 0) {
      $mensaje = "<div class='noerror'>Enlace creado.<br />Acceda a trav&eacute;s de un cliente SFTP a ".$ruta_sftp."</div>";
    }

    else {
      $mensaje = "<div class='error'>No se ha podido crear el enlace.</div>";
    }

  }else if(isset($_POST['mover_zip']) && !empty($_POST['mover_zip']) && isset($_POST['zip2mv']) && !empty($_POST['zip2mv'])){	
    $zip = htmlspecialchars($_POST['zip2mv']);
    $pai = $_SESSION['id_grupo'];
    $ruta = explode("/",$zip);
    $nombre = $ruta[3]; // nombre del zip
    $long_nombre= strlen($usuario) + 14;
    $nombre_zip = substr($nombre, 0,$long_nombre);

    if(file_exists($zip) && is_readable($zip)) {
      $tmp = posix_getpwuid(fileowner($zip));
      $propietario = $tmp["name"];

      if($propietario == $_SESSION['usuario']) {
	exec(escapeshellcmd($sudo . " " . $mv . " " .$zip." /SCRATCH/".$pai."/".$usuario."/".$nombre_zip.".zip"), $output, $ret_code);
      }
      else {
	$ret_code = 1;
      }
    }
    else {
      $ret_code = 1;
    }

    if($ret_code == 0) {
      $long_nombre= strlen($directorio_raiz)+1+strlen($usuario);
      $mes = substr($zip,$long_nombre + 4,2);
      if(ereg("0[1-9]",$mes))
	$mes = substr($mes,1,1);
      $mensaje = "<div class='noerror'>Movido Zip de Sesion Web: "
	.substr($zip,$long_nombre + 6,2)." de "
	.$meses[$mes-1]." de "
	.substr($zip,$long_nombre + 0,4)." a las "
	.substr($zip, $long_nombre + 8,2).":"
	.substr($zip, $long_nombre + 10,2).":"
	.substr($zip, $long_nombre + 12,2)
	."<br />Fichero zip: /SCRATCH/".$pai."/".$usuario."/".$nombre_zip.".zip"
	."</div>";
    }
    else {
      $mensaje = "<div class='error'>Ha ocurrido un error al mover el archivo comprimido.</div>";
    }
  }else if(isset($_POST['borrar_ftp']) && !empty($_POST['borrar_ftp']) && isset($_POST['ftp2del']) && !empty($_POST['ftp2del'])){
    $ftp = htmlspecialchars($_POST['ftp2del']);

    if(file_exists($ftp) && is_readable($ftp)) {
      $tmp = posix_getpwuid(fileowner($ftp));
      $propietario = $tmp["name"];

      if($propietario == $_SESSION['usuario']) {
	exec(escapeshellcmd($rm . " " . $ftp), $output, $ret_code);
      }
      else {
	$ret_code = 1;
      }
    }
    else {
      $ret_code = 1;
    }

    if($ret_code == 0) {
      $long_nombre= strlen($directorio_raiz)+1+strlen($usuario);
      $mes = substr($ftp,$long_nombre + 4,2);
      if(ereg("0[1-9]",$mes))
	$mes = substr($mes,1,1);

      $mensaje = "<div class='noerror'>Borrado enlace SFTP para Sesion Web: "
	.substr($ftp,$long_nombre + 6,2)." de "
	.$meses[$mes-1]." de "
	.substr($ftp,$long_nombre + 0,4)." a las "
	.substr($ftp, $long_nombre + 8,2).":"
	.substr($ftp, $long_nombre + 10,2).":"
	.substr($ftp, $long_nombre + 12,2)."</div>";
    }
    else {
      $mensaje = "<div class='error'>Ha ocurrido un error al borrar el enlace SFTP a la sesi&oacute;n web.</div>";
    }
  }else if(isset($_POST['cancelar_job']) && !empty($_POST['cancelar_job']) && isset($_POST['jobid']) && !empty($_POST['jobid']) && isset($_POST['dir']) && !empty($_POST['dir'])){

    $jobid = $_POST['jobid'];
    $dir = $_POST['dir'];
    exec(escapeshellcmd($sudo . " -u ".$usuario." ". $qdelweb ." ".$jobid), $output, $ret_code);

    if($ret_code == 0) {
      $long_nombre= strlen($directorio_raiz)+1+strlen($usuario);
      $mes = substr($dir,$long_nombre + 4,2);
      if(ereg("0[1-9]",$mes))
	$mes = substr($mes,1,1);
      $mensaje = "<div class='noerror'>Cancelado trabajo ".$jobid.", sesion Web: "
	.substr($dir,$long_nombre + 6,2)." de "
	.$meses[$mes-1]." de "
	.substr($dir,$long_nombre + 0,4)." a las "
	.substr($dir, $long_nombre + 8,2).":"
	.substr($dir, $long_nombre + 10,2).":"
	.substr($dir, $long_nombre + 12,2)."</div>";
    }

    else {
      $mensaje = "<div class='error'>Ha ocurrido un error al cancelar el trabajo ".$jobid.".</div>";
    }
  }

  $escaneado = scandir($directorio_raiz);
  $dir_usuario = array(); // para guardar directorios de sesiones
  $zip_usuario = array(); // para guardar los zip de sesiones
  $ftp_usuario = array(); // para guardar la ruta del directorio a descargar por ftp

  $existe_zip = false;
  $existe_ftp = false;

  foreach($escaneado as $dir){
    if(ereg("^".$usuario."[0-9]{14}--[A-Za-z0-9]*[.]zip",$dir)){
      if(count($dir_usuario)>0){
	// comprobamos que sea el zip del directorio anterior
	$dir_anterior = $dir_usuario[count($dir_usuario)-1][0];
	if($dir_anterior.".zip" == $dir){
	  array_unshift($dir_usuario[count($dir_usuario)-1],$dir);
	}else{
	  array_push($zip_usuario,$dir);
	}
      }else{
	array_push($zip_usuario,$dir);
      }
    }else if(ereg("^".$usuario."[0-9]{14}--[A-Za-z0-9]*[.]ftp",$dir)){ // es un fichero oculto con la ruta de FTP
      if(count($dir_usuario)>0){
	// comprobamos que sea el ftp del directorio anterior
	$dir_anterior = $dir_usuario[count($dir_usuario)-1][0];
	if($dir_anterior.".ftp" == $dir){
	  array_unshift($dir_usuario[count($dir_usuario)-1],$dir);
	}else{
	  array_push($ftp_usuario,$dir);
	}
      }else{
	array_push($ftp_usuario,$dir);
      }
    }else if(ereg("^".$usuario."[0-9]{14}",$dir)){ //es un directorio del usuario
      $temp = scandir($directorio_raiz."/".$dir);
      array_unshift($temp , $dir);
      array_push($dir_usuario, $temp);

    }
  }

  //menu de opciones

  echo <<<EOD
<div class='toc'>
        <div id="toc__inside">
                <ul class="toc">
EOD;
if(count($dir_usuario)>0){
  echo '<li class="level1"><div class="li"><span class="li"><a href="' . $directorio_scripts . 'sesion_web.php#todas_sesiones" class="toc">Sesiones Web</a></span></div></li>';
  echo '<li class="level1"><div class="li"><span class="li">&nbsp;</span></div></li>';
}
if(count($zip_usuario)>0){
  echo '<li class="level1"><div class="li"><span class="li"><a href="' . $directorio_scripts . 'sesion_web.php#todos_zip" class="toc">Directorios comprimidos en zip</a></span></div></li>';
  echo '<li class="level1"><div class="li"><span class="li">&nbsp;</span></div></li>';
}
if(count($ftp_usuario)>0){
  echo '<li class="level1"><div class="li"><span class="li"><a href="' . $directorio_scripts . 'sesion_web.php#todos_ftp" class="toc">Directorios copiados en SCRATCH</a></span></div></li>';
  echo '<li class="level1"><div class="li"><span class="li">&nbsp;</span></div></li>';
}
echo '<li class="level1"><div class="li"><span class="li"><a href="' . $directorio_scripts . 'sesion_web.php#enviar" class="toc">Enviar trabajos a trav&eacute;s de la Web</a></span></div></li>';
echo '<li class="level1"><div class="li"><span class="li">&nbsp;</span></div></li>';

echo "</ul></div></div>";

echo "<div class='error' style='background-color:white;color:#dd3b15;font-weight:bold;font-size:120%;border:2px solid green;'>SISTEMA DE ENVIO DE TRABAJOS A TRAV&Eacute;S DE LA WEB EN PRUEBAS (Beta)<br />Puede enviarnos cualquier sugerencia para mejorar.</div>";

echo $mensaje ; // mensaje de la operación que llega por post

echo "<h1><a name='todas_sesiones'></a>Sesiones Web<a href='#contenido' class='subir'>Subir</a></h1>";

echo "<p></a>A continuaci&oacute;n puede ver comprobar que ficheros han generado cada uno de los trabajos que haya enviado a trav&eacute;s de este modo al portal web. Puede comprobar si los trabajos est&aacute;n en ejecuci&oacute;n (o completados) o si a&uacute;n continua en cola.</p>";

if(count($dir_usuario)==0){
  echo "<p>No tiene trabajos enviados a trav&eacute;s de la web.</p>";
}else{
  echo "<p>Se han encontrado ".count($dir_usuario)." sesion/es web a su nombre.</p>";
}

$i = 0;

echo "<ul>";
foreach($dir_usuario as $dir){
  
  $directorio = $dir[0];
  $long_nombre = strlen($usuario) ;
  $mes = substr($directorio,$long_nombre + 4,2);
  if(ereg("0[1-9]",$mes))
    $mes = substr($mes,1,1);

  echo "<li>Sesion Web: "
    .substr($directorio,$long_nombre + 6,2)." de "
    .$meses[$mes-1]." de "
    .substr($directorio,$long_nombre + 0,4)." a las "
    .substr($directorio, $long_nombre + 8,2).":"
    .substr($directorio, $long_nombre + 10,2).":"
    .substr($directorio, $long_nombre + 12,2)." <a href='#sesion".$i."'>Ver</a> </li>";
  
  $i++;
}
echo "</ul>";

$i = 0;
foreach($dir_usuario as $dir){
  $existe_zip = false;
  $directorio = array_shift($dir);
  $zip = '';
  $existe_ftp = false;
  $ftp = '';
  if(ereg("^".$usuario."[0-9]{14}--[A-Za-z0-9]*[.]zip",$directorio)){
    $existe_zip = true;	
    $zip = $directorio;
    $directorio = array_shift($dir);
  }else if(ereg("^".$usuario."[0-9]{14}--[A-Za-z0-9]*[.]ftp",$directorio)){
    $existe_ftp = true;
    $ftp = $directorio;
    $directorio = array_shift($dir);
  }
  $long_nombre = strlen($usuario) ;
  $mes = substr($directorio,$long_nombre + 4,2);
  if(ereg("0[1-9]",$mes)) 
    $mes = substr($mes,1,1);

  echo "<h2><a name='sesion".$i."'></a>Sesion Web: "
    .substr($directorio,$long_nombre + 6,2)." de "
    .$meses[$mes-1]." de "
    .substr($directorio,$long_nombre + 0,4)." a las "
    .substr($directorio, $long_nombre + 8,2).":"
    .substr($directorio, $long_nombre + 10,2).":"
    .substr($directorio, $long_nombre + 12,2)."<a class='negro' href='#todas_sesiones'>Subir</a></h2>";
  // comprobamos el estado del trabajo, consultado el jobid que esta en el ficheor sge_jobid.txt

  if(in_array("sge_jobid.txt",$dir)){
    // consultamos el estado del trabajo
    $jobid = file_get_contents($directorio_raiz.$directorio."/sge_jobid.txt");
    
    $estado = exec("source " . $sge_config . " ; " . $qstat . " -u '".$usuario."*' | " . $grep . " ".$jobid);
    echo "<form method=post action=" . $directorio_scripts . "sesion_web.php name=borrar_job>";
    echo "<input type=hidden value='".$jobid."' name=jobid id=jobid class='oculto'/>";
    echo "<input type=hidden value='" . $directorio_raiz.$directorio."' name=dir id=dir class='oculto'/>";

    echo "<div class='noerror'>";
    if(count($estado) == 1 && empty($estado[0])) 
      echo "<strong>Trabajo Terminado</strong>";	
    else{
      
      $estado = split(" ",$estado);
      if(in_array("r", $estado)){
	echo "<strong>Trabajo en ejecuci&oacute;n</strong>";
      }else if(in_array("qw",$estado)){
	echo "<strong>Trabajo en cola</strong>";
      }else if(in_array("hqw",$estado)){
	echo "<strong>Trabajo en cola esperando por otros trabajos</strong>";
      }else if(in_array("Eqw",$estado)){
	echo "<strong>Trabajo en error, consulte los logs</strong>";
      }else if(in_array("dr",$estado)){
	echo "<strong>Trabajo pendiente de borrar</strong>";
      }
      echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Cancelar trabajo" name="cancelar_job" class="boton_form"/>';
    }
    echo "</div>";
    echo "</form>";
  }

  if(count($dir) > 4){ // hay más entradas que . .. fichero_Entrada fichero_trabajo
    // listamos los ficheros encontrados
    echo "<p>Para ver el contenido del fichero haga click en el nombre del mismo.</p>";
    echo "<p>Para descargar el fichero haga click con el bot&oacute;n derecho y seleccione 'Guardar enlace como...'.</p>";
    echo "<ul>";
    foreach($dir as $file){
      if($file != "." && $file != ".."){
	if(is_dir($directorio_raiz.$directorio."/".$file)){
	  echo "<li>Directorio ".$file;
	  echo mostrar_dir_as_ul($directorio."/".$file);
	  echo "</li>";
	}else{
	  $file_escape = str_replace(".","__",$file);
	  echo "\n<form name='descargar_fichero_".$file_escape."' action='descargas.php' method='POST'>";
	  echo "\n<input type='hidden' value='".$directorio."' name='ruta' id='ruta' />";
	  echo "\n<input type='hidden' value='".$file."' name='doc' id='doc' />";
	  echo "\n</form>";
	  echo "\n<li><a onclick='document.forms.descargar_fichero_".$file_escape.".submit();return false;' >".$file."</a></li>";
	}
      }
    }
    echo "</ul>";
    $result = exec("du -s " . $directorio_raiz.$directorio);	
    $result = split("	",$result);
    $tamanio_dir = $result[0];
    echo "<p><strong>Opciones sobre el directorio:</strong></p>";
    echo "<p style='margin-left:30px;'><strong>IMPORTANTE:</strong>Compruebe que el trabajo se ha completado. Si no se ha completado y mueve o borra el directorio dar&aacute; errores el trabajo.</p>";

    echo "<form method='post' action='" . $directorio_scripts . "sesion_web.php' name='borrar_dir'>";
    echo "<input type='hidden' name='dir2del' value='" . $directorio_raiz.$directorio."' class='oculto' />";
    echo "<ul><li><input type='submit' value='Borrar directorio' name='borrar' />";
    if($existe_ftp){
      echo "<input type='hidden' name='enlaceFTP' value='".$ftp."' class='oculto' />";
      echo "Si borra el directorio, perderá el enlace para acceso SFTP; para no perder el directorio, utilice la opci&oacute;n <em>Mover directorio a SCRATCH</em>";
    }
    echo "</li></ul>";
    echo "</form>";
    echo "<form method=post action=" . $directorio_scripts ."sesion_web.php name='mover_dir'>";
    echo "<input type='hidden' name='dir2mv' value='" . $directorio_raiz.$directorio."'  class='oculto'  />";
    echo "<ul><li><input type='submit' value='Mover directorio a SCRATCH' name='mover' />";
    if($existe_ftp){
      echo "<input type='hidden' name='enlaceFTP' value='".$ftp."' class='oculto' />";
      echo "Si mueve el directorio, el &uacute;nico acceso ser&aacute; a trav&eacute;s de su SCRATCH</em>";
    }
    echo "</li></ul>";

    echo "</form>";
    echo "<ul><li>";
    if($existe_zip ){
      echo "<form method=post action=" . $directorio_scripts . "sesion_web.php name='mover_zip'>";
      echo "<input type='hidden' name='zip2mv' value='" . $directorio_raiz .$zip."' class='oculto'  />";
      echo "Ha comprimido los ficheros de la sesion web, ahora puede descargarlos en el <a href='".$directorio_descargas.$zip."'>fichero comprimido</a>; o ";
      echo "<input type='submit' value='Mover comprimido a SCRATCH' name='mover_zip' />";
      echo "</form>";
    }else if($existe_ftp){
      echo "<p>Ha creado un enlace para acceder al contenido de este directorio a trav&eacute;s de SFTP</p>";
      $enlace = file_get_contents($directorio_raiz.$ftp);
      echo "<p>Ruta de enlace: ".$enlace."</p>";
    }else if($tamanio_dir > 4000000 ){  // directorio mayor de 4GB, no se comprime
      echo "<form method=post action=" . $directorio_scripts . "sesion_web.php name='sftp_dir'>";
      echo "<input type='hidden' name='dir2sftp' value='". $directorio_raiz . $directorio."'  class='oculto'  />";
      echo "El directorio <strong>es demasiado grande</strong> para poder comprimirlo, utilice un cliente SFTP para descargar el contenido del mismo.&nbsp;";
      echo "<input type='submit' value='Crear enlace para SFTP' name='sftp' />";
      echo "</form>";

    }else{
      echo "<form method=post action=" . $directorio_scripts . "sesion_web.php name='comprimir_dir'>";
      echo "<input type='hidden' name='dir2zip' value='" . $directorio_raiz.$directorio."'  class='oculto'  />";
      echo "<input type='submit' value='Comprimir directorio' name='comprimir' /> <strong>NOTA:</strong> Esta operaci&oacute;n puede tardar varios minutos, por favor sea paciente.";
      echo "</form>";
    }
    echo "</li></ul>";
  }else{
    echo "<p>El trabajo aún no se está ejecutando, deberá esperar a que el trabajo entre en cola.</p>";
    echo "<form method=post action=" . $directorio_scripts . "sesion_web.php name='borrar'>";
    echo "<input type='hidden' name='dir2del' value='" . $directorio_raiz . $directorio."'  class='oculto'  />";
    echo "<p>Si ha cancelado el trabajo puede optar por ";
    echo "<input type='submit' value='Borrar el directorio' name='borrar' />";
    echo "</p>";
    echo "</form>";
  }
  
  $i++;
}

if(count($zip_usuario)>0){
  echo "<h1><a name='todos_zip'></a>Directorios comprimidos en zip<a href='#contenido' class='subir'>Subir</a></h1>";
  echo "<p>Se han encontrado ".count($zip_usuario)." fichero/s zip de sesiones web que ya no est&aacute;n disponibles.</p>";
  foreach ($zip_usuario as $zip){
    $long_nombre = strlen($usuario) ;
    $mes = substr($zip,$long_nombre + 4,2);
    if(ereg("0[1-9]",$mes))
      $mes = substr($mes,1,1);

    echo "<p><strong>Sesion Web:</strong> "
      .substr($zip,$long_nombre + 6,2)." de "
      .$meses[$mes-1]." de "
      .substr($zip,$long_nombre + 0,4)." a las "
      .substr($zip, $long_nombre + 8,2).":"
      .substr($zip, $long_nombre + 10,2).":"
      .substr($zip, $long_nombre + 12,2)."</p>";
    echo "<p style='margin-left:20px;display:inline;'>Puede descargar en el <a href='" . $directorio_descargas .$zip."'>fichero comprimido</a>; o ";
    echo "<form method=post action=" . $directorio_scripts . "sesion_web.php name='mover_zip' style='display:inline;margin:0px;'>";
    echo "<input type='hidden' name='zip2mv' value='" . $directorio_raiz . $zip."' class='oculto'  />";
    echo "<input type='submit' value='Mover comprimido a SCRATCH' name='mover_zip' />";
    echo "</form></p>";
  }
}

if(count($ftp_usuario)>0){
  echo "<h1><a name='todos_ftp'></a>Acceso por SFTP<a href='#contenido' class='subir'>Subir</a></h1>";
  echo "<p>Recuerde que tiene ".count($ftp_usuario)." directorio/s en /SCRATCH con contenido generado a trav&eacute;s de una sesi&oacute;n web anterior.</p>";
  echo "<p>Para acceder a los directorios que se indican utilice un cliente SFTP.</p>";
  foreach ($ftp_usuario as $ftp){
    $file = file_get_contents($ftp);
    echo "<form method=post action=" . $directorio_scripts . "sesion_web.php name='borrar_ftp' style='display:inline;margin:0px;'>";
    echo "<input type='hidden' name='ftp2del' value='" . $directorio_raiz . $ftp."' class='oculto'  />";
    echo "<pre class='amarillo'>Directorio: ".$file;
    echo "<input type='submit' value='Borrar enlace' name='borrar_ftp' /></pre>";
    echo "</form>";
  }
}

echo <<<EOD
<br />
<h1><a name='enviar'></a>Enviar trabajos<a href="#contenido" class="subir">Subir</a></h1>

<p>Se han establecido dos formas de enviar un trabajo, en la m&aacute;s sencilla el usuario indica la aplicaci&oacute;n que va a utilizar, la cantidad de procesadores, la cola que va a utilizar y adjunta el fichero de entrada (o varios de ellos). En la m&aacute;s compleja el usuario deber&aacute; indicar el script que desea enviar as&iacute; como las opciones que desea utilizar para el trabajo enviado SGE. Seleccione una u otra en funci&oacute;n de sus necesidades.</p>
<form name='radiobuttons'>
<p>Modo de envio: <br /><input type='radio' name='tipo_submit' value='1'  onClick='checkTipoSubmit();' checked /> Sencillo <br /><input type='radio' name='tipo_submit' value='2' onClick='checkTipoSubmit();' /> Complejo</p>
</form>

EOD;



echo "<div id='sencillo'><p>Para enviar un trabajo a trav&eacute;s de la web, seleccione las opciones que desea para su trabajo y suba el fichero de entrada que utilizar&aacute; la aplicaci&oacute;n seleccionada.</p>";

formulario_trabajo_simple($usuario);

echo "</div>";

echo "<div id='complejo' style='display:none;'>";

formulario_trabajo_complejo($usuario);

echo "</div>";



echo <<<EOD

<div id="f1_upload_process" style="display:none;" class="noerror"><center>Por favor, espere a que se suba el/los fichero/s<br /><img src="images/ajax-loader2.gif" /><br />Esta operaci&oacute;n depende de su conexi&oacute;n y puede tardar unos minutos, sea paciente.</center></div>
<p id="result" style="display:block;"></p>
<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>


EOD;


}
?>
