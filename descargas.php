<?php
/*
 * descargas.php
 * 
 * Copyright (C) 2010, 2012 Leire López, Rafael Arco
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

if(isset($_SESSION['usuario'])) {
  $usuario = $_SESSION['usuario'];
  if(isset($_POST['doc']) && isset($_POST['ruta'])){
    $doc = $_POST['doc'];
    $ruta = $_POST['ruta'];
    $error = false;
    $mensaje = '';

    $documento = str_replace("__", ".", $_POST["doc"]);
    
    if(strpos($ruta,$usuario) === false){ // la ruta no tiene el nombre dle usuario, asi que otro usuario está intentando acceder
      $error = true;
      $mensaje = "Debe ser propietario del fichero para acceder a su descarga.";
    }else{
      if(file_exists($directorio_raiz .$ruta."/".$documento) &&  filesize($directorio_raiz.$ruta."/".$documento) > 0){	
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=".$documento);
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($diirectorio_raiz.$ruta."/".$documento));

	if (!@readfile($directorio_raiz.$ruta."/".$documento)){ 	
	  $error = true;
	  $mensaje = "Ha sido imposible descargar el fichero, int&eacute;ntelo m&aacute;s tarde.";
	}else $error= false;
      }else{
	$error = true;
	$mensaje = "El fichero seleccionado no existe.";
      }
    }	
  }else{
    $error = true;
    $mensaje = "No seleccionado fichero a descargar.";
    
  }
  if($error && $mensaje != ''){
    cabecera();
    menu();
    inicio_pagina();
    rastro('Descargas');
    titulo_pagina('Descargas');
    echo "<div id='contenido'>";
    echo "<p>".$mensaje."</p>";
    echo "</div>";
    fin_pagina();
    pie();
  }

}
else {
  cabecera();
  menu();
  inicio_pagina();
  rastro('Descargas');
  titulo_pagina('Descargas');
  echo "<div id='contenido'>";
  requerir_identificacion();
  echo "</div>";
  fin_pagina();
  pie();
}


?>
