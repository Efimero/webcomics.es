<?php
## Archivo: models/mod_usuario.php
## Descripción: Modelo usuario.  Usa "ion-auth".
##		Al modificar valores, tener en cuenta que las comprobaciones
##		realizadas aquí son mínimas.  La mayor parte de estas han de
##		hacerse antes de asignar los nuevos valores.
## Autor: Guillermo Martínez <gmartinez(at)visionados.com>



  class Mod_usuario extends CI_Model
  {
  ## Nombre de los campos
    private $fCampos = array ('id', 'username', 'group_id', 'group',
	'group_description', 'email', 'nombre', 'apellidos');
  ## Datos del usuario.
    private $fValores;
  ## Nuevos valores.
    private $fNuevosValores = array ();



  ## Constructor.
    public function __construct ()
    {
      parent::__construct ();
      $this->load->config('ion_auth', TRUE);
      $this->load->library('ion_auth');
    }



  ## Actualiza los datos.
    public function Actualiza ()
    {
      $Resultado = $this->ion_auth->update_user ($this->Valor ('id'),
	$this->fNuevosValores);
      if ($Resultado)
	foreach ($this->fNuevosValores as $Nombre => $Valor)
	  $this->fValores[$Nombre] = $Valor;
      return $Resultado;
    }



  ## Cierra la sesión.
    public function CierraSesion ()
    {
      $this->ion_auth->logout ();
    }



  ## Crear un nuevo usuario.
    public function Crear ($Nombre, $Clave, $CorreoE)
    {
      return $this->ion_auth->register (
	$Nombre, $Clave, $CorreoE,
	array ('nombre' => $Nombre, 'apellidos' => '')
      );
    }



  ## Comprueba si el usuario está identificado, esto es, si existe la sesión.
    public function EstaIdentificado ()
    {
      return $this->ion_auth->logged_in ();
    }



  ## Realiza la identificación, iniciando la sesión.
  ## Devuelve .F. si el usuario o la clave no son válidos.
    public function IniciaSesion ($Nombre, $Clave, $Recordar)
    {
      return $this->ion_auth->login ($Nombre, $Clave, $Recordar);
    }



  ## Modifica la clave.
  ## Devuelve .T. o .F. según el cambio haya tenido o no éxito.
    public function ModificaClave ($ClaveVieja, $ClaveNueva)
    {
      if (!$this->ion_auth->change_password ($this->Valor ('username'), $ClaveVieja, $ClaveNueva))
	throw new ExceptionWee ($this->ion_auth->errors (), 'Mod_usuario', 'ModificaClave');
    }



  ## Obtiene los datos del usuario.  Si no se indica, obtiene los del usuario
  ## identificado.
    public function Obtiene ($Id = false)
    {
      if ($Id)
	$Datos = $this->ion_auth->get_user ($Id);
      else
	$Datos = $this->ion_auth->get_user ();
      if (!$Datos)
	return false;
      foreach ($this->fCampos as $Nombre)
	$this->fValores[$Nombre] = $Datos->$Nombre;
      return true;
    }



  ## Establece nuevos valores.
    public function PonValor ($Campo, $Valor)
    {
      if (in_array ($Campo, $this->fCampos))
	$this->fNuevosValores[$Campo] = $Valor;
    }



  ## Devuelve el valor solicitado o .F. si no existe.
    public function Valor ($Campo)
    {
      return isset ($this->fValores[$Campo]) ? $this->fValores[$Campo] : false;
    }
  }
