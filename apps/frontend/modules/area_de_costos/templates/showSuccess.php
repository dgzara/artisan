<h1>Ver &Aacute;rea de Costo </h1>
<table class="one-table">
  <tbody>
    <tr>
      <th>Nº:</th>
      <td><?php echo $area_de_costos->getId() ?></td>
    </tr>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $area_de_costos->getNombre() ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Administracion_Costos_EditarAreasDeCosto")):?>
        <span class="modificar"><a href="<?php echo url_for('area_de_costos/edit?id='.$area_de_costos->getId()) ?>">Modificar</a></span>
    &nbsp;-&nbsp;
    <?php endif;?>
        <span class="volver"><a href="<?php echo url_for('area_de_costos/index') ?>">Volver</a></span>
</div>

