<?php
## Archivo: models/base_modelo.php
## Descripción: Define clases base para modelos.
##		Al modificar valores, tener en cuenta que las comprobaciones
##		realizadas aquí son mínimas.  La mayor aprte de estas han de
##		hacerse antes de asignar los nuevos valores.
## Autor: Guillermo Martínez <gmartinez(at)visionados.com>

if (!defined ('BASEPATH'))
  exit ('No direct script access allowed'); 



## Base para modelos que almacenan su información en la base de datos.
  class TBaseModelo extends Model
  {
  ## Nombre de la tabla.
    protected $fNombreTabla;
  ## Información del modelo obtenida de la base de datos.
    private $fDatos = null;



  ## Constructor.
    public function __construct ($aNombreTabla)
    {
      parent::Model ();
      $this->fNombreTabla = $aNombreTabla;
    }
  }



## Base para modelos que almacenan su información en la base de datos que
## pueden modificarse.
  class TModeloMutable extends TBaseModelo
  {
  }
