<table class="one-table">
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $descriptor_de_producto->getId() ?></td>
    </tr>
    <tr>
      <th>Producto:</th>
      <td><?php echo $descriptor_de_producto->getProducto()->getNombre() ?></td>
    </tr>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $descriptor_de_producto->getNombre() ?></td>
    </tr>
    <tr>
      <th>Valor:</th>
      <td><?php echo $descriptor_de_producto->getValor() ?></td>
    </tr>
    <tr>
      <th>Fecha:</th>
      <td><?php echo $descriptor_de_producto->getFecha() ?></td>
    </tr>
    <tr>
      <th>Detalle:</th>
      <td><?php echo $descriptor_de_producto->getDetalle() ?></td>
    </tr>
  </tbody>
</table>

<hr />
<?php if($sf_user->hasPermission("Ver_Administracion_ProductosEnVenta_EditarDescripctores")):?>
<a href="<?php echo url_for('descriptor_producto/edit?id='.$descriptor_de_producto->getId()) ?>">Editar</a>
&nbsp;-&nbsp;
<?php endif;?>
<a href="<?php echo url_for('descriptor_producto/index') ?>">Volver</a>
