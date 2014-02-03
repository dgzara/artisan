<h3>Orden de Compra N°<?php echo $orden_compra->getNumero() ?>. Solicita: <?php echo $sf_user->getGuardUser()->getName() ?></h3> 

<table border="1" CELLPADDING="10" CELLSPACING="1">
  <tbody>
    <tr>
      <th width="30%">Fecha de Emisión:</th>
      <td width="70%"><?php echo $orden_compra->getDateTimeObject('fecha')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Proveedor:</th>
      <td><?php echo $orden_compra->getProveedor()->getEmpresaNombre() ?></td>
    </tr>
    <tr>
      <th>Condiciones:</th>
      <td><?php echo $orden_compra->getCondiciones() ?></td>
    </tr>
    <tr>
      <th>Fecha de Recepción:</th>
      <td><?php if($orden_compra->getFechaRecepcion()!=NULL) echo $orden_compra->getDateTimeObject('fecha_recepcion')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Recepcionista Artisan:</th>
      <td><?php echo $orden_compra->getEncargado_Recepcion() ?></td>
    </tr>
    <tr>
      <th>Guía de Despacho:</th>
      <td><?php echo $orden_compra->getGuia_Despacho() ?></td>
    </tr>
    <tr>
      <th>Encargado de Despacho:</th>
      <td><?php echo $orden_compra->getEncargado_Despacho() ?></td>
    </tr>
    <tr>
      <th>Fecha de Facturación:</th>
      <td><?php if($orden_compra->getFechaFactura()!=NULL) echo $orden_compra->getDateTimeObject('fecha_factura')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>N° de Factura:</th>
      <td><?php echo $orden_compra->getN_Factura() ?></td>
    </tr>
    <tr>
      <th>Fecha de Pago:</th>
      <td><?php if($orden_compra->getFechaPago()!=NULL) echo $orden_compra->getDateTimeObject('fecha_pago')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Forma de Pago:</th>
      <td><?php echo $orden_compra->getForma_Pago() ?></td>
    </tr>
    <tr>
      <th>N° de Documento:</th>
      <td><?php echo $orden_compra->getN_Documento() ?></td>
    </tr>
    <tr>
      <th>Próxima Acción:</th>
      <td><?php echo $orden_compra->getAccion() ?></td>
    </tr>
</table><p></p>
<table border="1" CELLPADDING="10" CELLSPACING="1">
        
            <tr>
      <th width="28%">Insumo</th>
      <th width="30%">Detalle</th>
      <th width="11%">Cantidad</th>
      <th width="14%">Precio</th>
      <th width="17%">Valor Neto</th>
            </tr>
        
            <?php
        $insumos = $orden_compra->getInsumos();
        $total = 0;
        $unidades =0;
        foreach($insumos as $insumo){
            echo '<tr><td>'.$insumo->getNombre().' '.$insumo->getPresentacion().$insumo->getUnidad().'</td><td>'.$insumo->getDetalle().'</td><td>'.$insumo->getCantidad().'</td><td>'.number_format($insumo->getPrecio(),'0',',','.').'</td><td>'.number_format($insumo->getPrecio()*$insumo->getCantidad(),'0',',','.').'</td></tr>';
            $total+=$insumo->getPrecio()*$insumo->getCantidad();
            $unidades+=$insumo->getCantidad();
        }
        echo '<tr><th></th><th></th><th></th><th>Neto</th><th>'.number_format($total,'0',',','.').'</th></tr>';
        echo '<tr><th></th><th></th><th></th><th>IVA</th><th>'.number_format($total*0.19,'0',',','.').'</th></tr>';
        echo '<tr><th></th><th></th><th></th><th>Total</th><th>'.number_format($total*1.19,'0',',','.').'</th></tr>';
        ?>
          </table>
        

