<!DOCTYPE html>
<html>
 <head>
  <title><?= $Titulo ?></title>
  <meta charset="utf8" />
 </head>
 <body>
  <h1>Webcómics En Español</h1>
<?php if ($EstaIdentificado): ?>
  <p><a href="<?= site_url ('usuario/salir'); ?>">Cerrar sesión</a>.</p>
<?php else: ?>
  <p><a href="<?= site_url ('usuario/acceder'); ?>">Iniciar sesión</a>.</p>
<?php endif; ?>
