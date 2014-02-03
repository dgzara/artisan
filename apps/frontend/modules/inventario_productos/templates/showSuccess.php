<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $inventario_productos->getId() ?></td>
    </tr>
    <tr>
      <th>Planta:</th>
      <td><?php echo $inventario_productos->getPlantaId() ?></td>
    </tr>
    <tr>
      <th>Producto:</th>
      <td><?php echo $inventario_productos->getProductoId() ?></td>
    </tr>
    <tr>
      <th>Cantidad:</th>
      <td><?php echo $inventario_productos->getCantidad() ?></td>
    </tr>
    <tr>
      <th>Fecha:</th>
      <td><?php echo $inventario_productos->getFecha() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $inventario_productos->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $inventario_productos->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('inventario_productos/edit?id='.$inventario_productos->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('inventario_productos/index') ?>">List</a>
