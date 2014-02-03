<table class="one-table">
  <tbody>
    <tr>
      <th>Cantidad Inicial:</th>
      <td><?php echo $lote->getCantidad() ?></td>
    </tr>
    <tr>
      <th>Cantidad Actual:</th>
      <td><?php echo ($lote->getCantidad_Actual()-$lote->getVendidas()) ?></td>
    </tr>
    <tr>
      <th>Cantidad Dañada Valdivia:</th>
      <td><?php echo $lote->getCantidad_Danada() ?></td>
    </tr>
    <tr>
      <th>Cantidad Fuera de Formato Valdivia:</th>
      <td><?php echo $lote->getCantidad_Ff() ?></td>
    </tr>
    <tr>
      <th>Cantidad de Control Valdivia:</th>
      <td><?php echo $lote->getCc_Valdivia() ?></td>
    </tr>
    <tr>
      <th>Cantidad Vendida:</th>
      <td><?php echo $lote->getVendidas() ?></td>
    </tr>
    <tr>
      <th>Cantidad Recibida:</th>
      <td><?php echo $lote->getCantidad_Recibida() ?></td>
    </tr>
    <tr>
      <th>Cantidad Dañada Santiago:</th>
      <td><?php echo $lote->getCantidad_Danada_Stgo() ?></td>
    </tr>
    <tr>
      <th>Cantidad Fuera de Formato Santiago:</th>
      <td><?php echo $lote->getCantidad_Ff_Stgo() ?></td>
    </tr>
    <tr>
      <th>Cantidad de Control Santiago:</th>
      <td><?php echo $lote->getCc_Santiago() ?></td>
    </tr>
  </tbody>
</table>
<p class='demo'><a href='/web/lote/generarqr/<?php echo $lote->getId() ?>' target='_blank' class='demo'>Generar QR</a></p>



