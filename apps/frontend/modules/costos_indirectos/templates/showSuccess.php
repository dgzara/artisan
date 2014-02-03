<h1>Ver Costo Indirecto Nº<?php echo $costos_indirectos->getId() ?></h1>
<table class="one-table">
  <tbody>
    <tr>
      <th width="30%">Nombre:</th>
      <td><?php echo $costos_indirectos->getNombre() ?></td>
    </tr>
    <tr>
      <th>Area de costos:</th>
      <td><?php echo $costos_indirectos->getAreaDeCostos()->getNombre() ?></td>
    </tr>
    <tr>
      <th>Centro de costos:</th>
      <td><?php echo $costos_indirectos->getCentroDeCostos()->getNombre() ?></td>
    </tr>
    <tr>
      <th>Lugar:</th>
      <td><?php echo $costos_indirectos->getBodega()->getNombre() ?></td>
    </tr>
    <tr>
      <th>Monto:</th>
      <td><?php echo '$'.$formato->format($costos_indirectos->getMonto(),'d','CLP') ?></td>
    </tr>
    <tr>
      <th>Fecha:</th>
      <td><?php echo date('d M Y', strtotime($costos_indirectos->getFecha())) ?></td>
    </tr>
    <tr>
      <th>Detalle:</th>
      <td><?php echo $costos_indirectos->getDetalle() ?></td>
    </tr>
    <tr>
      <th>Archivo Adjunto:</th>
      <td><a href="<?php echo url_for('homepage')?>uploads/costosindirectos/<?php echo $costos_indirectos->getArchivoAdjunto() ?>" target="_blank">Descargar</a></td>
    </tr>
  </tbody>
</table>

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Costos_CostosIndirectos_Editar")):?>
    <span class="modificar"><a href="<?php echo url_for('costos_indirectos/edit?id='.$costos_indirectos->getId()) ?>">Modificar</a></span>
    &nbsp;-&nbsp;
    <?php endif;?>
    <span class="volver"><a href="<?php echo url_for('costos_indirectos/index') ?>">Volver</a></span>
</div>