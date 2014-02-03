<h1 >Detalles del Insumo (<?php echo $insumo->getNombreCompleto()?>)</h1>
<br>

<table class="one-table">
  <tbody>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $insumo->getNombre() ?></td>
    </tr>
    <tr>
      <th>Peso/Volumen:</th>
      <td><?php echo $insumo->getPresentacion() ?></td>
    </tr>
    <tr>
      <th>Unidad:</th>
      <td><?php echo $insumo->getUnidad() ?></td>
    </tr>
    <tr>
      <th>Stock Crítico:</th>
      <td><?php echo $insumo->getStockCritico() ?></td>
    </tr>
    <tr>
      <th>Conversion:</th>
      <td><?php echo $insumo->getStockCritico() ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Administracion_Insumos_EditarInsumosParaComprar")):?>
<span class="modificar"><a href="<?php echo url_for('insumo/edit?id='.$insumo->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
<span class="volver"><a href="<?php echo url_for('insumo/index') ?>">Volver</a></span>
</div>
