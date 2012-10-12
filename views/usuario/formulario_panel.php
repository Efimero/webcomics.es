<h2>Panel del usuario</h2>
<?php if (isset ($Mensaje) and $Mensaje != ''): ?>
   <p class="aviso"><?= $Mensaje ?></p>
<?php endif; ?>
  <?=form_open ('usuario/modificar'); ?>
    <fieldset><legend>Información personal</legend>
      <p><label for="username">Usuario:</label> <input id="username" name="username" type="text" value="<?= $Usuario ?>" /></p>
      <p><label for="nombre">Nombre:</label> <input id="nombre" name="nombre" type="text" value="<?= $Nombre ?>" /></p>
      <p><label for="apellidos">Apellidos:</label> <input id="apellidos" name="apellidos" type="text" value="<?= $Apellidos ?>" /></p>
      <p><label for="email">Correo electrónico:</label> <input id="email" name="email" type="email" value="<?= $CorreoE ?>" /></p>
      <p><input type="submit" />
    </fieldset>
  </form>
  <?=form_open ('usuario/modificar'); ?>
    <fieldset><legend>Información de acceso</legend>
      <p><label for="clave">Clave:</label> <input id="clave" name="clave" type="password" /></p>
      <p><label for="clave2">Confirmar clave:</label> <input id="clave2" name="clave2" type="password" /></p>
      <p><strong>Nota:</strong> Para modificar la clave de acceso es obligatorio indicar la clave actual de acceso.</p>
      <p><label for="clave_anterior">Clave actual:</label> <input id="clave_anterior" name="clave_anterior" type="password" /></p>
      <p><input type="submit" />
    </fieldset>
  </form>
