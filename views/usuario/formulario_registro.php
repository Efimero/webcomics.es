  <p><a href="<?= site_url ('usuario/acceder'); ?>">Acceder</a>.</p>
  <form action="" method="post"><fieldset>
   <legend>Registro de un nuevo formulario</legend>
<?php if (isset ($Mensaje)): ?>
   <div class="aviso"><?= $Mensaje ?></div>
<?php endif; ?>
   <p><label for="useername">Nombre:</label> <input name="username" id="username" type="text" value="<?= $Nombre ?>" /></p>
   <p><label for="clave">Contraseña:</label> <input name="clave" id="clave" type="password" /></p>
   <p><label for="clave2">Confirmar contraseña:</label> <input name="clave2" id="clave2" type="password" /></p>
   <p><label for="email">Correo electrónico:</label> <input name="email" id="email" type="email" value="<?= $CorreoE ?>" /></p>
   <!-- No en la versión actualp>Se enviará un correo de confirmación bla bla bla...</p -->
   <p><input name="acepta_condiciones" id="acepta_condiciones" type="checkbox" <?= $Acepta ?>/> <label for="acepta_condiciones">Acepto las <a href="condiciones">condiciones</a>.</label></p>
   <p><input type="submit" /></p>
  </fieldset></form>
