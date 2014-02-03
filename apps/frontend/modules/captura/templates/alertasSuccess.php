<div align="left">
        <form name="form" method="post" action="alertas">
            <p><select name="accion" onchange="this.form.submit()">
                <option value="">-- Filtrar por Estado --</option>
                <option value="todas">Todas</option>
                <option value="revisadas">Revisadas</option>
                <option value="noRevisadas">No Revisadas</option>
            </select> </p>
        </form>
  </div>

<p></p>
<h1>Lista de Alertas:  <?php echo $modo ?></h1>

<table >
  <thead>
    <tr>
      <th></th>
      <th>Producto</th>
      <th>Local</th>
      <th>Stock Cr&iacute;tico</th>
      <th>Stock Existente</th>
      <th>Fecha</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($capturas as $captura): ?>
    <tr>
      <td><a href="<?php echo url_for('captura/show?id='.$captura->getId()) ?>">Ver</a></td>
      <td><?php echo $captura->getProducto()->getNombre() ?></td>
      <td><?php echo $captura->getLocal()->getCliente()->getName().' '.$captura->getLocal()->getNombre() ?></td>
      <td><?php echo $captura->getProducto()->getStockCritico() ?></td>
      <td><?php echo $captura->getStock() ?></td>
      <td><?php echo $captura->getFecha() ?></td>
      <td><?php if(!$captura->getAlertado()) echo '<form name="form" method="post" action="revisado">
            <input type="hidden" name="accion" value="'.$captura->getId().'">
            <p><input type="submit" value="Revisado"/></p>
        </form>'?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
