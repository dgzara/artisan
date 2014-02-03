<h1 >Orden de Venta N°<?php echo $orden_venta->getNumero() ?></h1>

<table border="1" CELLPADDING="10" CELLSPACING="1">
  <tbody>
    <tr>
      <th width="30%">Fecha de Emisión:</th>
      <td width="70%"><?php echo $orden_venta->getDateTimeObject('fecha')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Cliente:</th>
      <td><?php echo $orden_venta->getCliente()->getName() ?></td>
    </tr>
    <tr>
      <th>Local:</th>
      <td><?php echo $orden_venta->getLocal()->getNombre() ?></td>
    </tr>
    <tr>
      <th>N° de O.C.:</th>
      <td><?php echo $orden_venta->getNOc() ?></td>
    </tr>
    <tr>
      <th>Condiciones:</th>
      <td><?php echo $orden_venta->getCondiciones() ?></td>
    </tr>
    <tr>
      <th>Fecha de Envío:</th>
      <td><?php if($orden_venta->getFechaEnvio()!=NULL) echo $orden_venta->getDateTimeObject('fecha_envio')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Guía de Despacho:</th>
      <td><?php echo $orden_venta->getGuiaDespacho() ?></td>
    </tr>
    <tr>
      <th>Encargado de Despacho:</th>
      <td><?php echo $orden_venta->getEncargadoDespacho() ?></td>
    </tr>
    <tr>
      <th>Fecha de Facturación/Boleta:</th>
      <td><?php if($orden_venta->getFechaBf()!=NULL) echo $orden_venta->getDateTimeObject('fecha_bf')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Factura/Boleta:</th>
      <td><?php echo $orden_venta->getBoletaFactura() ?></td>
    </tr>
    <tr>
      <th>N° de Factura/Boleta:</th>
      <td><?php echo $orden_venta->getNBf() ?></td>
    </tr>
    <tr>
      <th>Fecha de Recepción:</th>
      <td><?php if($orden_venta->getFechaRecepcion()!=NULL) echo $orden_venta->getDateTimeObject('fecha_recepcion')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Recepcionista en Local:</th>
      <td><?php echo $orden_venta->getEncargadoRecepcion() ?></td>
    </tr>
    <tr>
      <th>Fecha de Pago:</th>
      <td><?php if($orden_venta->getFechaPago()!=NULL) echo $orden_venta->getDateTimeObject('fecha_pago')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Forma de Pago:</th>
      <td><?php echo $orden_venta->getFormaPago() ?></td>
    </tr>
    <tr>
      <th>N° Documento:</th>
      <td><?php echo $orden_venta->getNDocumento() ?></td>
    </tr>
    <tr>
      <th>Próxima Acción:</th>
      <td><?php echo $orden_venta->getAccion() ?></td>
    </tr>
</table><p></p>
<table border="1" CELLPADDING="10" CELLSPACING="1">
      <tr>
      <th width="28%">Producto</th>
      <th width="20%">Detalle</th>
      <th width="11%">Cantidad</th>
      <th width="14%">Precio</th>
      <th width="13%">Valor Neto</th>
      <th width="14%">Descuento</th>
            </tr>
            <?php
        $productos = $orden_venta->getProductos();
        $total = 0;
        $unidades =0;
        foreach($productos as $producto){
            $descuento = $descuento + ($producto->getDescuento()/100)*$producto->getPrecio()*$producto->getCantidad();
            echo '<tr><td>'.$producto->getNombreCompleto().'</td><td>'.$producto->getDetalle().'</td><td>'.$producto->getCantidad().'</td><td>'.number_format($producto->getPrecio(),'0',',','.').'</td><td>'.number_format($producto->getPrecio()*$producto->getCantidad(),'0',',','.').'</td><td>'.number_format($producto->getDescuento(),'0',',','.').'%</td></tr>';
            $total+=$producto->getPrecio()*$producto->getCantidad();
            $unidades+=$producto->getCantidad();
        }
        echo '<tr><th></th><th></th><th></th><th>Neto</th><th>'.number_format($total,'0',',','.').'</th><th></th></tr>';
        echo '<tr><th></th><th></th><th></th><th>Descuento</th><th>'.number_format($descuento,'0',',','.').'</th><th></th></tr>';
        echo '<tr><th></th><th></th><th></th><th>IVA</th><th>'.number_format(($total-$descuento)*0.19,'0',',','.').'</th><th></th></tr>';
        echo '<tr><th></th><th></th><th></th><th>Total</th><th>'.number_format(($total-$descuento)*1.19,'0',',','.').'</th><th></th></tr>';
        ?>
        </tbody>
          </table>
