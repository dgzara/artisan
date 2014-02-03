<h1>Detalles de la Asociación</h1>
<table class="one-table">
  <tbody>
    <tr>
      <th>Producto:</th>
      <td><?php echo $producto_competencia_producto->getProducto()->getNombre()." ".$producto_competencia_producto->getProducto()->getPresentacion()." ".$producto_competencia_producto->getProducto()->getUnidad() ?></td>
    </tr>
    <tr>
      <th>Producto competencia:</th>
      <td><?php echo $producto_competencia_producto->getProductoCompetencia()->getNombreCompleto() ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Movil_ProductosCompetencia_EditarAsociaciones")):?>
<span class="modificar"><a href="<?php echo url_for('producto_competencia_producto/edit?id='.$producto_competencia_producto->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
<span class="volver"><a href="<?php echo url_for('producto_competencia_producto/index') ?>">Volver</a></span>
</div>