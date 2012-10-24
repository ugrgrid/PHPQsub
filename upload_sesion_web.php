<?php
/*
 * upload_sesion_web.php
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

function anadir_opciones_sge($param){

  $opciones='';
  $cola = '';
  $correo = '';
  $status_correo = '';
  while (list($key, $value) = each($param)) {
    //echo "$key => $val";
    if(!empty($value)){ // que esté relleno
      switch($key){
      case 'proc': $opciones.='-pe openmpi '; break;
      case 'cola': $opciones.='-q '; $cola = $value; break; 
      case 'name_job': $opciones .= '-N '; break;
      case 'holdjid': $opciones .= '-hold_jid '; break;
      case 'standar_o': $opciones .='-o ';break;
      case 'standar_e': $opciones .='-e ';break;
      case 'merge_output': $opciones .='-j Y'; $value = ''; break;
      case 'reservation': $opciones .='-R y'; $value = ''; break;
      case 'tasks': $opciones .='-t 1-';break;
      case 'input_file': $opciones .='-i ';break;
      case 'resources': $opciones .='-l '.$cola.','; $cola = ''; break;
      case 'enviroment': $opciones .='-v ';break;
      case 'send_mail': $opciones .='-M '; break;
      case 'check_start_status_mail': $status_correo = 'b'; $value = '';
      case 'check_end_status_mail': 
	if($status_correo != '') $status_correo .= '|';
	$status_correo = 'e';
	$value = '';
	break;
      case 'check_suspend_status_mail':
	if($status_correo != '') $status_correo .= '|';
	$status_correo = 's';
	$value = '';
	break;
      case 'check_abort_status_mail':
	if($status_correo != '') $status_correo .= '|';
	$status_correo = 'a';
	$value = '';
	break;
      case 'shell': $opciones .= '-S '; break;
      case 'subir':
	if($status_correo != ''){
	  $opciones .= '-m '.$status_correo; 
	}
	$value = '';
	break;
      default: $value = '';
      }
      $opciones.=$value." ";
    }
  }
  if($cola != ''){
    $opciones .= "-l ".$cola." ";
  }
  return $opciones;

}

function randomstring($longitud){

  $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  $cadena  = $caracteres{ rand(0,strlen($caracteres)-1) };
  for($i=0; $i < $longitud; $i++){
    $cadena .= $caracteres{ rand(0,strlen($caracteres)-1) };
  }
  return $cadena;
  
}

//$usuario = $_POST['usuario'];
$usuario = $_SESSION['usuario'];
$result = '';
$result2 = 0;
$sesion_actual = date("YmdHis");

//print_r($_POST);

if((!empty($_FILES["myfile"])) && ($_FILES['myfile']['error'] == 0)) {
  
  $filename = basename($_FILES['myfile']['name']);
  $ext = substr($filename, strrpos($filename, '.') + 1);
  //no ocupar mas de 10MB
  if (( $_FILES["myfile"]["size"] < 10000000)){
    $carpeta = $usuario.$sesion_actual."--".randomstring(64);//path donde se va a guardar el fichero
    $directorio = $directorio_raiz.$carpeta;
    $newname = $directorio.'/'.$filename;
    if(!is_dir($directorio) && !mkdir($directorio, 0750)) {
      $result2 = -1;
    }
    //comprueba que no exista en el servidor 
    else if (!file_exists($newname)) {
      //colocar el fichero en la ubicacion indicada 
      if ((move_uploaded_file($_FILES['myfile']['tmp_name'],$newname))) {
	$result = "Hecho! El fichero de entrada ha sido salvado.";
	$resultado = chdir($directorio);
	exec($qconvert . " ".$newname);
	$total_adicionales = count($_FILES["archivos"]["name"]);
	$error= false;
	$entra = false;
	if (isset ($_FILES["archivos"])  && $total_adicionales > 0) {
	  for ($i = 0; $i < $total_adicionales && !$error; $i++){
	    if(!empty($_FILES['archivos']['name'][$i])){
	      // colocamos los ficheros adicionales en el mismo directorio
	      $newname_add = $directorio.'/'.basename($_FILES["archivos"]["name"][$i]);
	      if((move_uploaded_file($_FILES['archivos']['tmp_name'][$i],$newname_add))) {
		exec($qconvert . " ".$newname_add);
		$error = false;
		$entra = true;
	      }else $error = true;
	      if($error){
		$result = "Error: Ha habido un problema con los ficheros adicionales!";
		$result2 = -1;
	      }
	    }
	  }
	}
	if(!$error && $entra){
	  $result.="<br />Fichero/s adicionales salvado/s";
	}

	$salida = array();
	$resultado1 = exec (escapeshellcmd($sudo . " " . $chown . " -R ".$usuario." ".$directorio));
	$resultado2 = exec (escapeshellcmd($sudo . " " . $chmod . " 770 ".$directorio));
	$result = $result.implode("\n",$salida);
	if($resultado1 != 0 || $resultado2 != 0){
	  $result = "<p>Error: Un problema ha ocurrido durante la subida fichero/s!</p>";
	  $result2 = -1;
	}
	$resultado = chdir($directorio);
	$salida = '';
	
	if(isset($_POST['aplicacion']) && !empty($_POST['aplicacion'])){ // envio de trabajo sencillo
	  if(isset($_POST["proc"]) && !empty($_POST["proc"]) &&
	     isset($_POST["cola"]) && !empty($_POST["cola"]) && 
	     !$error && $result2 == 0){
	    $apli = htmlspecialchars($_POST['aplicacion']);
	    $proc = htmlspecialchars($_POST['proc']);
	    $cola = htmlspecialchars($_POST['cola']);
	    $salida = exec(escapeshellcmd($sudo . ' -u '.$usuario.' ' . $directorio_aplicaciones . $apli.' '.$proc.' '.$filename.' '.$cola));
	  }else{
	    $result .= "Error: no se ha podido enviar el trabajo, compruebe el mensaje de error y corrija la informaci&oacute;n que envia.";
	    $result2 = -1;
	  }
	  
	}else{
	  if( isset($_POST["proc"]) && !empty($_POST["proc"]) &&
	      isset($_POST["cola"]) && !empty($_POST["cola"]) && 
	      !$error && $result2 == 0 ){ // envio de trabajo complejo

	    //guardamos las opciones en un fichero sge_options.txt
	    $error = false;
	    $fh = fopen("sge_options.txt", 'w') or $error = true;
	    if($error){
	      $result .= "<p>No se han podido guardar las opciones marcadas, es posible que el trabajo se ejecute erroneamente.</p>";
	      $result2 = -1;
	    }else{
	      fwrite($fh, anadir_opciones_sge($_POST));
	      fclose($fh);
	      exec($qconvert . " sge_options.txt");
	    }
	    //escribimos el script del trabajo que se va a enviar
	    $error = false;
	    $fh = fopen("sge_script.sh","w") or $error = true;
	    if($error || empty($_POST['script_qsub'])){
	      $result .= "<p>No se ha podido guardar el script del trabajo, el trabajo no se ha enviado.</p>";
	      $result2 = -1;
	    }else{
	      fwrite($fh, $_POST['script_qsub']);
	      fclose($fh);
	      exec($qconvert . " sge_script.sh");
	    }
	    if(!$error && $result2 == 0 )
	      $salida = exec($sudo . ' -u '.$usuario.' ' . $qsub . ' sge_options.txt sge_script.sh');
	    else{
	      $result .= "Error: no se ha podido enviar el trabajo, compruebe el mensaje de error y corrija la informaci&oacute;n que envia.";
	      $result2 = -1;
	    }
	  }else{
	    $result .= "Error: no se ha podido enviar el trabajo, compruebe el mensaje de error y corrija la informaci&oacute;n que envia.";
	    $result2 = -1;
	  }
	}
	if(strlen($salida)>0){ //procesamos la salida
	  $result2 = 0;
	  $result = "Trabajo enviado.<br />".$salida;
	  $salida = split(" ",$salida); // Your job 31494 ("agua.dat.job") has been submitted
	  $jobid = $salida[2];
	  $error = false;
	  $fh = fopen("sge_jobid.txt", 'w') or $error = true;
	  if($error){
	    $result .= "<p>No se ha podido guardar el jobid, para comprobar el estado del trabajo consulte <a href='index.php?page=trabajos-usuario\'>Mis trabajos</a></p>";
	    $result2 = -1;
	  }else{
	    fwrite($fh, $jobid);
	    fclose($fh);
	  }
	}else {
	  $result .="<p>".$salida."</p>";
	}
	//echo ".\n".$result."----";
	//echo $result2."\n---------";
	
      } else {
	$result = "Error: Un problema ha ocurrido durante la subida del fichero!";
	$result2 = -1;
      }
    } else {
      $result = "Error: El fichero ".$_FILES["myfile"]["name"]." ya existe.";
      $result2 = 2;
    }
  } else {
    $result = "Error: Sólo ficheros inferiores a 10MB.";
    $result2 = 3;
  }
} else {
  $result = "Error: No existe fichero que subir.";
  $result2 = 1;
}



echo '<script language="javascript" type="text/javascript">'.
'parent.stopUpload('.$result2.',\''.($result).'\');'.
'</script> ';

?>
