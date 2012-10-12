<?php
## Archivo: controllers/usuario.php
## Descripción: Controlador para gestionar la información de un usuario.
## Autor: Guillermo Martínez J. <http://www.burdjia.com/>



  class Usuario extends CI_Controller
  {
  ## Constructor.
    public function __construct ()
    {
      parent::__construct ();
      $this->load->helper ('url');
      $this->load->model ('mod_usuario', 'usuario');
    }



  ## La cabecera y el pie de la página.
    private function CabeceraHTML ($Titulo)
    {
      $this->load->view ('usuario/cabecera_html', array (
	'Titulo' => $Titulo,
	'EstaIdentificado' => $this->usuario->EstaIdentificado ()
      ));
    }

    private function PieHTML ()
    {
      $this->load->view ('usuario/pie_html');
    }



  ## Construye la página.  La plantilla se toma del subdirectorio 'usuario/'.
    private function PaginaNormal ($Plantilla, $Titulo, $Valores)
    {
      $this->CabeceraHTML ($Titulo);
      $this->load->view ("usuario/$Plantilla", $Valores);
      $this->PieHTML ();
    }



  ## Acceso de usuario.
  ## Además de los datos de identificación, se le puede pasar la ruta de una
  ## página en el parámetro "rp", para que redirija a esta tras identificarse.
  ## En caso de no indicar ninguna, redirige a la página del panel de usuario.
  ## Nótese que el parámetro "$Redireccionar" únicamente se usa para mantener
  ## el valor del campo "rp", pero no se usa en la redirección.
    public function acceder ($Mensaje=false, $Redireccionar=false)
    {
      $NombreUsuario = '';
    # Comprueba si debe redireccionar tras acceder.
      if (!$Redireccionar)
	$Redireccionar = $this->input->post ('rp');
      if (!$Mensaje)
	$Mensaje = $this->session->flashdata ('mensaje');
    # Comprobar si recibió datos.
      $this->load->library ('form_validation');
      $this->form_validation->set_rules ('username', 'Usuario', 'required');
      $this->form_validation->set_rules ('clave', 'Clave', 'required');
      if (!$this->form_validation->run ()) {
	if (validation_errors () != '')
	  $Mensaje = 'Debe indicar un nombre y una clave de acceso.';
      }
      else {
      # Comprobar si es un acceso válido.
	$NombreUsuario = $this->input->post ('username');
	$Clave = $this->input->post ('clave');
	$Recordar = $this->input->post ('recordar') == 'true';
	if ($this->usuario->IniciaSesion ($NombreUsuario, $Clave, $Recordar)) {
	# Volvemos a solicitiar la redirección para evitar que se intente
	# acceder donde no se debe vía URL.  De esta manera no puede hacerse un
	# "...usuario/acceder/usuario/panel/111...".
	  if ($Redireccionar = $this->input->post ('rp'))
	    redirect ($Redireccionar);
	  else
	    redirect ('usuario/panel');
	  return;
	}
	$Mensaje = 'Nombre o clave de acceso erróneo.';
      }
      $Ocultos = array ();
      if ($Redireccionar)
	$Ocultos['rp'] = $Redireccionar;
    # Muestra el formulario.
      $this->load->helper ('form');
      $this->PaginaNormal (
	'formulario_identificacion',
	'Identificar al usuario.',
	array (
	  'Mensaje' => $Mensaje,
	  'Nombre' => $NombreUsuario,
	  'Ocultos' => $Ocultos
      ));
    }



  ## Cierra la sesión.
    public function salir ()
    {
      $this->usuario->CierraSesion ();
      $this->CabeceraHTML ('Cerrar sesión');
      echo '<p>Sesión cerrada.</p>';
      $this->PieHTML ();
    }



  ## Por defecto muestra el panel de usuario.
    public function index ()
    {
      if ($this->usuario->EstaIdentificado ())
	$this->panel ();
      else
	$this->acceder ('', 'usuario/panel');
    }



  ## Muestra la lista de usuarios.
    public function lista ()
    {
      $this->CabeceraHTML ('Identificar al usuario.');
      echo 'Lista de usuarios.';
      $this->PieHTML ();
    }



  ## Permite modificar la información del usuario.
    public function modificar ()
    {
      if ($this->usuario->EstaIdentificado ()) {
	$Mensaje = '';
	$this->usuario->Obtiene ();
      # Comprueba si recibió datos.
	if ($this->input->post ('username') != false) {
	  $ListaCampos = array ('username'=>'Usuario', 'nombre' => 'Nombre',
	    'apellidos' => 'Apellidos', 'email' => 'Correo electrónico');
	# Modifica datos personales.
	  $this->load->library ('form_validation');
	  foreach ($ListaCampos as $Campo => $Nombre)
	    $this->form_validation->set_rules ($Campo, $Nombre, 'required');
	  if (!$this->form_validation->run ())
	    $Mensaje = validation_errors ();
	  else {
	  # modificar
	    foreach ($ListaCampos as $Campo => $Nombre)
	      $this->usuario->PonValor ($Campo, $this->input->post ($Campo));
	    if (!$this->usuario->Actualiza ())
	      $Mensaje = 'No se pudo actualizar la información.&nbsp; Inténelo más tarde.';
	    else
	      $Mensaje = 'La información ha sido actualizada.';
	  }
	}
	elseif ($this->input->post ('clave') != false) {
	# Modifica la contraseña.
	  $this->load->library ('form_validation');
	  $this->form_validation->set_rules('clave_anterior', 'Clave actual', 'required');
	  $this->form_validation->set_rules('clave', 'Clave', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[clave2]');
	  $this->form_validation->set_rules('clave2', 'Confirmar clave', 'required');
	  if (!$this->form_validation->run ())
	    $Mensaje = validation_errors ();
	  else try {
	    $this->usuario->ModificaClave ($this->input->post ('clave_anterior'), $this->input->post ('clave'));
	    $Mensaje = 'Contraseña modificada.';
	  }
	  catch (Exception $Error) {
	    $Mensaje = $Error->getMessage ();
	  }
	}
      # Muestra el formulario.
	$this->load->helper ('form');
	$this->PaginaNormal ('panel', 'Panel de usuario.', array (
	  'Nombre' => $this->usuario->Valor ('nombre'),
	  'Apellidos' => $this->usuario->Valor ('apellidos'),
	  'CorreoE' => $this->usuario->Valor ('email'),
	  'Usuario' => $this->usuario->Valor ('username'),
	  'Mensaje' => $Mensaje
	));
      }
      else
	$this->acceder ('Debe identificarse para modificar la información.',
	  'usuario/panel'.(($IdUsuario!=false)?"/$IdUsuario":''));
    }



  ## Página del panel de usuario.  Muestra los datos del usuario.
  ## Por defecto muestra los datos del usuario identificado.
    public function panel ($IdUsuario = false)
    {
      if (!$IdUsuario) {
	if ($this->usuario->EstaIdentificado ()) {
	  $this->usuario->Obtiene ();
	# Muestra el formulario.
	  $this->load->helper ('form');
	  $Plantilla = 'formulario_panel';
	  $Titulo = 'Panel de usuario.';
	}
	else {
	  $this->acceder ('Debe identificarse para poder acceder al panel.',
	    'usuario/panel'.(($IdUsuario!=false)?"/$IdUsuario":''));
	  return;
	}
      }
      else {
	if ($this->usuario->Obtiene ($IdUsuario)) {
	  $Plantilla = 'panel';
	  $Titulo = 'Panel del usuario '.$this->usuario->Valor ('username');
	}
	else {
	  show_error ("Usuario $IdUsuario no encontrado", 404);
	  return;
	}
      }
      $this->PaginaNormal ($Plantilla, $Titulo, array (
	'Nombre' => $this->usuario->Valor ('nombre'),
	'Apellidos' => $this->usuario->Valor ('apellidos'),
	'CorreoE' => $this->usuario->Valor ('email'),
	'Usuario' => $this->usuario->Valor ('username'),
      ));
    }



  ## Registro de un nuevo usuario.
    public function registrar ()
    {
      if ($this->usuario->EstaIdentificado ())
	redirect ('usuario');
    # Comprueba si recibió datos.
      $this->load->library ('form_validation');
      $this->form_validation->set_rules('username', 'Nombre', 'required|xss_clean');
      $this->form_validation->set_rules('clave', 'Clave', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[clave2]');
      $this->form_validation->set_rules('clave2', 'Confirmar clave', 'required');
      $this->form_validation->set_rules('email', 'Correo electrónico', 'required|valid_email');
      $Mensaje = '';
      if ($this->form_validation->run ()) {
	if (!$this->input->post ('acepta_condiciones'))
	  $Mensaje = '<p>Debe aceptar las condiciones de uso.</p>';
	else {
	  if (!$this->usuario->Crear (
	      $this->form_validation->set_value ('username'),
	      $this->form_validation->set_value ('clave'),
	      $this->form_validation->set_value ('email'))
	  )
	    $Mensaje = $this->ion_auth->errors();
	  else {
	    $this->session->set_flashdata ('mensaje', 'Usuario creado');
	    redirect ('usuario/acceder');
	  }
	}
      }
      $Datos = array (
	'Mensaje' => validation_errors ().$Mensaje,
	'Nombre' => $this->form_validation->set_value ('username'),
	'CorreoE' => $this->form_validation->set_value ('email'),
	'Acepta' => $this->input->post ('acepta_condiciones') ? 'checked="checked"' : ''
      );
      $this->PaginaNormal ('formulario_registro', 'Registrar usuario', $Datos);
    }
  }
