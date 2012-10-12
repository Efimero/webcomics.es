<?php
## Archivo: libraries/base_controlador.php
## Descripción: Define una base para los controladores de la web.
## Autor: Guillermo Martínez J. <http://www.burdjia.com/>

  class TBaseControlador extends CI_Controller
  {
  ## Lista de archivos JavaScript a añadir a la cabecera.
    private $fArchivosJavaScript = Array ();



  ## Constructor.  En principio únicamente existe como enlace a
  ## la parte PHP4 de CodeIgniter.
    public function __construct ()
    {
      parent::__construct ();
    }



  ## Carga la vista "cabecera_html".
    protected function CabeceraHTML ($Titulo)
    {
      $this->load->view ('cabecera_html', Array (
	'Titulo' => "$Titulo",
	'ArchivosJavaScript' => $this->fArchivosJavaScript
      ));
    }


  ## Por defecto, el index no lleva a ninguna parte.
    public function index ()
    {
      log_message ('error', '[TBaseControlador::index] No implementado');
      show_404 ('index.php');
    }



  ## Carga la vista "pie_html".
    protected function PieHTML ()
    {
      $this->load->view ('pie_html');
    }
  }
