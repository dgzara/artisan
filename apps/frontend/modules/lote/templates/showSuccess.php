<h1 >Lote N°<?php echo $lote->getNumero() ?></h1>
<br>

<table class="one-table">
  <tbody>
    <tr>
      <th width="30%">Fecha de Elaboración:</th>
      <td><?php echo $lote->getDateTimeObject('fecha_elaboracion')->format('d M Y'); ?></td>
    </tr>
    <tr>
      <th>Fecha de Vencimiento:</th>
      <td><?php
      if($lote->getFecha_Salida()!=NULL)
        echo $vencimiento
      ?></td>
    </tr>
    <tr>
      <th>Producto:</th>
      <td><?php echo $lote->getProducto()->getNombreCompleto() ?></td>
    </tr>
    <tr>
      <th>Comentarios:</th>
      <td><?php echo $lote->getComentarios() ?></td>
    </tr>
    <tr>
      <th>Cantidad Inicial:</th>
      <td><?php echo $lote->getCantidad() ?></td>
    </tr>
    <tr>
      <th>Cantidad Actual:</th>
      <td><?php echo ($lote->getCantidad_Actual()-$lote->getVendidas()) ?></td>
    </tr>
    <tr>
      <th>Fecha de Ingreso a Maduración:</th>
      <td><?php if($lote->getFecha_Entrada() !=NULL) echo $lote->getDateTimeObject('fecha_entrada')->format('d M Y'); ?></td>
    </tr>
        <tr>
      <th>Fecha de Retiro de Maduración:</th>
      <td><?php if($lote->getFecha_Salida() !=NULL) echo $lote->getDateTimeObject('fecha_salida')->format('d M Y'); ?></td>
    </tr>
        <tr>
      <th>Cantidad Vendida:</th>
      <td><?php echo $lote->getVendidas() ?></td>
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
      <th>Fecha Empaque:</th>
      <td><?php if($lote->getFecha_Empaque() !=NULL) echo $lote->getDateTimeObject('fecha_empaque')->format('d M Y'); ?></td>
    </tr>
    <tr>
      <th>Cantidad de Control Valdivia:</th>
      <td><?php echo $lote->getCc_Valdivia() ?></td>
    </tr>
    <tr>
      <th>Fecha Envío:</th>
      <td><?php if($lote->getFecha_Envio() !=NULL) echo $lote->getDateTimeObject('fecha_envio')->format('d M Y'); ?></td>
    </tr>
            <tr>
      <th>Medio de Transporte:</th>
      <td><?php echo $lote->getMedio_Transporte() ?></td>
    </tr>
        <tr>
      <th>N° Documento:</th>
      <td><?php echo $lote->getN_Documento() ?></td>
    </tr>
        <tr>
      <th>Fecha Recepción:</th>
      <td><?php if($lote->getFecha_Recepcion() !=NULL) echo $lote->getDateTimeObject('fecha_recepcion')->format('d M Y'); ?></td>
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
    <tr>
      <th>Próxima Acción:</th>
      <td><?php echo $lote->getAccion() ?></td>
    </tr>

  </tbody>
</table>

<hr />

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Produccion_ProduccionReal_EditarLotesElaborados")):?>
        <span class="modificar"><a href="<?php echo url_for('lote/edit?id='.$lote->getId()) ?>">Modificar</a></span>
    &nbsp;-&nbsp;
    <?php endif;?>
    <span class="volver"><a href="<?php echo url_for('lote/index') ?>">Volver</a></span>
    &nbsp;-&nbsp;
    <span class="generarQR"> <a href='/quesosar/web/lote/generarqr/<?php echo $lote->getId() ?>' target='_blank' class='demo'>Generar QR</a></span>
</div>