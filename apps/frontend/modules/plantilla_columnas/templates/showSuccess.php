<h1>Detalles de la columna de pauta: "<?php echo $plantilla_columna->getNombre() ?>"</h1>

<table class="one-table">
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $plantilla_columna->getId() ?></td>
    </tr>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $plantilla_columna->getNombre() ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Administracion_Pautas_EditarColumnas")):?>
    <span class="modificar"><a href="<?php echo url_for('plantilla_columnas/edit?id='.$plantilla_columna->getId()) ?>">Modificar</a></span>
    &nbsp;-&nbsp;
    <?php endif;?>
    <span class="volver"><a href="<?php echo url_for('plantilla_columnas/index') ?>">Volver</a></span>
</div>