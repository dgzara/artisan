<?php foreach ($productos as $producto): ?>
    <tr>
      <td><?php echo $producto->getCodigo() ?></td>
      <td><?php echo $producto->getRama()->getNombre() ?></td>
      <td><?php echo $producto->getNombre() ?></td>
      <td><?php echo $producto->getPresentacion().$producto->getUnidad() ?></td>
      <td><?php echo $producto->getDuracion() ?> días</td>
      <td><?php echo $producto->getMaduracion() ?> días</td>
      <td><?php echo $producto->getStockCritico() ?></td>
      <td><a href="<?php echo url_for('producto/show?id='.$producto->getId()) ?>">Ver</a></td>
      <?php if($sf_user->hasPermission("Ver_Administracion_ProductosEnVenta_EditarListaProductos")):?>
      <td><a href="<?php echo url_for('producto/edit?id='.$producto->getId()) ?>">Modificar</a></td>
      <td><?php echo link_to('Eliminar', 'producto/delete?id='.$producto->getId(), array('method' => 'delete', 'confirm' => '¿Se encuentra seguro de que desea eliminar este producto?')) ?></td>
      <?php endif;?>
    </tr>
<?php endforeach; ?>