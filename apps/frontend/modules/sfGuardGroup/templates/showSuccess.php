<h1>Detalles del Grupo</h1>
<br>

<table class="one-table">
  <tbody>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $group->getName() ?></td>
    </tr>
    <tr>
      <th>Descripción:</th>
      <td><?php echo $group->getDescription() ?></td>
    </tr>
    <tr>
      <th valign="top">Permisos:</th>
      <td>
          <?php $permisos = $group->getPermissions() ?>
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
<?php if($sf_user->hasPermission("Ver_Administracion_Usuarios_EditarGrupos")):?>
    <span class="modificar"><a href="<?php echo url_for('sfGuardGroup/edit?id='.$group->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
    <span class="volver"><a href="<?php echo url_for('sfGuardGroup/index') ?>">Volver</a></span>
</div>
