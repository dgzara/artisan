<h1>Detalles del Producto (<?php echo $producto->getNombreCompleto()?>)</h1>
<br>

<table class="one-table" >
  <tbody>
    <tr>
      <th>Área de Negocio:</th>
      <td><?php echo $producto->getRama()->getNombre() ?></td>
    </tr>
    <tr>
      <th>Nombre Producto:</th>
      <td><?php echo $producto->getNombre() ?></td>
    </tr>
    <tr>
      <th>Peso o Volumen de Producto:</th>
      <td><?php echo $producto->getPresentacion() ?></td>
    </tr>
    <tr>
      <th>Unidad:</th>
      <td><?php echo $producto->getUnidad() ?></td>
    </tr>
    <tr>
      <th>Ciclo de Vida Comercial:</th>
      <td><?php echo $producto->getDuracion() ?> días</td>
    </tr>
    <tr>
      <th>Ciclo de Vida Producción:</th>
      <td><?php echo $producto->getMaduracion() ?> días</td>
    </tr>
    <tr>
      <th>Stock Crítico de Inventario:</th>
      <td><?php echo $producto->getStockCritico() ?></td>
    </tr>
  </tbody>
</table>

<h2>Descriptores</h2>
<table class="one-table">
    <thead>
        <th>Insumo</th>
        <th>Cantidad</th>
        <th>Detalles</th>
    </thead>
    <?php foreach($producto->getDescriptores() as $descriptor): ?>
    <tbody>
        <tr>
            <td><?php echo $descriptor->getInsumo()->getNombreCompleto()?></td>
            <td><?php echo $descriptor->getCantidad() ?></td>
            <td><?php echo $descriptor->getDetalle() ?></td>
        </tr>
    </tbody>
    <?php endforeach;?>
</table>

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Administracion_ProductosEnVenta_EditarListaProductos")):?>
    <span class="modificar"><a href="<?php echo url_for('producto/edit?id='.$producto->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
    <span class="volver"><a href="<?php echo url_for('producto/index') ?>">Volver</a></span>
</div>
