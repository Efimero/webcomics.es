<?php
## Archivo: helpers/error_helper.php
## Descripción: Define ayudas para la gestión de errores.
## Autor: Guillermo Martínez J. <http://www.burdjia.com/>

## Extiende la clase de excepción para que haga un seguimiento en la bitácora.
  class ExceptionWee extends Exception
  {
  ## Constructor con información adicional de depuración.
    public function __construct ($Mensaje, $Clase='', $Metodo='')
    {
      parent::__construct ($Mensaje);
      $Pref ='';
      if ($Clase != '') {
	$Pref = $Clase;
	if ($Metodo != '')
	  $Pref .= "::$Metodo";
	$Pref = "[$Pref]";
      }
      log_message ('error', "$Pref$Mensaje");
    }
  }
