<h1>Detalles del Usuario <?php echo $usuario->getUsername() ?></h1>

<table class="one-table">
  <tbody>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $usuario->getFirstName() ?></td>
    </tr>
    <tr>
      <th>Apellido:</th>
      <td><?php echo $usuario->getLastName() ?></td>
    </tr>
    <tr>
      <th>Email:</th>
      <td><?php echo $usuario->getEmailAddress() ?></td>
    </tr>
    <tr>
      <th>Nick:</th>
      <td><?php echo $usuario->getUsername() ?></td>
    </tr>
    <tr>
      <th>Último Acceso:</th>
      <td><?php echo $usuario->getLastLogin() ?></td>
    </tr>
    <tr>
      <th>Grupos:</th>
      <td><?php $grupos = $usuario->getGroups() ?>
          <ul>
              <?php foreach($grupos as $grupo):?>
                    <li><?php echo $grupo->getName()?></li>
              <?php endforeach;?>
          </ul>
      </td>
    </tr>
    <tr>
      <th>Permisos:</th>
      <td>
          <?php $permisos = $usuario->getPermissions() ?>
          <ul>
              <?php foreach($permisos as $permiso):?>
                    <li><?php echo $permiso->getName()?></li>
              <?php endforeach;?>
          </ul>
      </td>
    </tr>
  </tbody>
</table>

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Administracion_Usuarios_EditarUsuario")):?>
    <span class="modificar"><a href="<?php echo url_for('sfGuardUser/edit?id='.$usuario->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
    <span class="volver"><a href="<?php echo url_for('sfGuardUser/index') ?>">Volver</a></span>
</div>
