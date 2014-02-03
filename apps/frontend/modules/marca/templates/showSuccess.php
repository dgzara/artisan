<h1>Detalles de la Marca</h1>
<table class="one-table">
  <tbody>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $marca->getNombre() ?></td>
    </tr>
    <tr>
      <th>Rubro:</th>
      <td><?php echo $marca->getRubro() ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Movil_ProductosCompetencia_EditarMarcas")):?>
    <span class="modificar"><a href="<?php echo url_for('marca/edit?id='.$marca->getId()) ?>">Modificar</a></span>
    &nbsp; - &nbsp;
    <?php endif;?>
    <span class="volver"><a href="<?php echo url_for('marca/index') ?>">Volver</a></span>
</div>