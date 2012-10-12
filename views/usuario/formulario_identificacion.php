  <p><a href="<?= site_url ('usuario/registrar'); ?>">Registrar</a>.</p>
  <?=form_open ('usuario/acceder', '', $Ocultos); ?><fieldset>
   <legend>Identificaci&oacute;n de usuario</legend>
<?php if (isset ($Mensaje) and $Mensaje != ''): ?>
   <p class="aviso"><?= $Mensaje ?></p>
<?php endif; ?>
   <p><label for="username">Usuario:</label> <input name="username" id="username" type="text" value="<?= $Nombre ?>" /></p>
   <p><label for="clave">Contraseña:</label> <input name="clave" id="clave" type="password" /></p>
   <p><input name="recordar" id="recordar" type="checkbox" value="true" /> <label for="recordar">Recordar sesión.</label></p>
   <p><input type="submit" /></p>
  </fieldset></form>
