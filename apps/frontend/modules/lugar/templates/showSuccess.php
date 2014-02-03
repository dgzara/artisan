<h1>Detalles del Lugar "<?php echo $bodega->getNombre() ?>" </h1>

<table>
  <tbody>
    <tr>
      <th>Nº:</th>
      <td><?php echo $bodega->getId() ?></td>
    </tr>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $bodega->getNombre() ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Administracion_Lugares_Editar")):?>
        <span class="modificar"><a href="<?php echo url_for('lugar/edit?id='.$bodega->getId()) ?>">Modificar</a></span>
    &nbsp;-&nbsp;
    <?php endif;?>
    <span class="volver"><a href="<?php echo url_for('lugar/index') ?>">Volver</a></span>
</div>