<h1>Detalles de Aspecto de Calidad</h1>
<table class="one-table">
  <tbody>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $aspecto_calidad->getNombre() ?></td>
    </tr>
    <tr>
      <th>Descripción:</th>
      <td><?php echo $aspecto_calidad->getDescripcion() ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Movil_AspectosCalidad_Editar")):?>
        <span class="modificar"><a href="<?php echo url_for('aspectos_calidad/edit?id='.$aspecto_calidad->getId()) ?>">Modificar</a></span>
    &nbsp;-&nbsp;
    <?php endif;?>
    <span class="volver"><a href="<?php echo url_for('aspectos_calidad/index') ?>">Volver</a></span>
</div>