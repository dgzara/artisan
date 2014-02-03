<h1 >Detalle Asociación</h1>
<br>
<table class="one-table">
  <tbody>
    <tr>
      <th>Cliente:</th>
      <td><?php echo $cliente_producto->getCliente()->getName() ?></td>
    </tr>
    <tr>
      <th>Producto:</th>
      <td><?php echo $cliente_producto->getProducto()->getNombreCompleto() ?></td>
    </tr>
    <tr>
      <th>Precio:</th>
      <td>$<?php echo $formato->format($cliente_producto->getPrecio(),'d','CLP')?></td>
    </tr>
    <tr>
      <th>Stock Crítico:</th>
      <td><?php echo $cliente_producto->getStockCritico() ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Administracion_ProductosEnVenta_EditarAsociaciones")):?>
<span class="modificar"><a href="<?php echo url_for('cliente_producto/edit?id='.$cliente_producto->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
<span class="volver"><a href="<?php echo url_for('cliente_producto/index') ?>">Volver</a></span>
</div>