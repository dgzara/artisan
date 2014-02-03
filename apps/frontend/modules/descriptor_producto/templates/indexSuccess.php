<h1>Lista de Productos</h1>

<table class="one-table">
  <thead>
    <tr>
      <th>Id</th>
      <th>Producto</th>
      <th>Nombre</th>
      <th>Valor</th>
      <th>Fecha</th>
      <th>Detalle</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($descriptor_de_productos as $descriptor_de_producto): ?>
    <tr>
      <td><a href="<?php echo url_for('descriptor_producto/show?id='.$descriptor_de_producto->getId()) ?>"><?php echo $descriptor_de_producto->getId() ?></a></td>
      <td><?php echo $descriptor_de_producto->getProducto()->getNombre() ?></td>
      <td><?php echo $descriptor_de_producto->getNombre() ?></td>
      <td><?php echo $descriptor_de_producto->getValor() ?></td>
      <td><?php echo $descriptor_de_producto->getFecha() ?></td>
      <td><?php echo $descriptor_de_producto->getDetalle() ?></td>
      <?php if($sf_user->hasPermission("Ver_Administracion_ProductosEnVenta_EditarDescripctores")):?>
      <?php endif;?>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
