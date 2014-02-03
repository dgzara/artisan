<table class="one-table">
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $inventario_materia_prima->getId() ?></td>
    </tr>
    <tr>
      <th>Insumo:</th>
      <td><?php echo $inventario_materia_prima->getInsumoId() ?></td>
    </tr>
    <tr>
      <th>Cantidad:</th>
      <td><?php echo $inventario_materia_prima->getCantidad() ?></td>
    </tr>
    <tr>
      <th>Fecha:</th>
      <td><?php echo $inventario_materia_prima->getFecha() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $inventario_materia_prima->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $inventario_materia_prima->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('inventario_materia_prima/edit?id='.$inventario_materia_prima->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('inventario_materia_prima/index') ?>">List</a>
