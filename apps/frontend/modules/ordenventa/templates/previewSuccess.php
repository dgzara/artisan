<table class="one-table">
  <tbody>
    <tr>
      <th>Fecha de Envío:</th>
      <td><?php if($orden_venta->getFechaEnvio()!=NULL) echo $orden_venta->getDateTimeObject('fecha_envio')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Fecha de Recepción:</th>
      <td><?php if($orden_venta->getFechaRecepcion()!=NULL) echo $orden_venta->getDateTimeObject('fecha_recepcion')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Fecha de Pago:</th>
      <td><?php if($orden_venta->getFechaPago()!=NULL) echo $orden_venta->getDateTimeObject('fecha_pago')->format('d M Y') ?></td>
    </tr>
     <tr>
      <th>Productos:</th>
      <td><table class="one-table">
        <thead>
            <tr>
      <th>Producto</th>
      <th>Detalle</th>
      <th>Cantidad</th>
      <th>Precio</th>
      <th>Valor Neto</th>
      <th>Descuento</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $productos = $orden_venta->getProductos();
        foreach($productos as $producto){
          $descuento=$descuento+($producto->getDescuento()/100)*$producto->getPrecio()*$producto->getCantidad();
            echo '<tr><td>'.$producto->getNombreCompleto().'</td><td>'.$producto->getDetalle().'</td><td>'.$producto->getCantidad().'</td><td style="text-align: right">$'.number_format($producto->getPrecio(),'0',',','.').'</td><td style="text-align: right">$'.number_format($producto->getPrecio()*$producto->getCantidad(),'0',',','.').'</td><td style="text-align: right">'.number_format($producto->getDescuento(),'0',',','.').'%</td></tr>';
        }
        echo '<tr><th></th><th></th><th></th><th>Neto</th><th style="text-align: right">'.$orden_venta->getValorNeto().'</th></tr>';
        echo '<tr><th></th><th></th><th></th><th>Descuento</th><th style="text-align: right">$'.number_format($descuento,'0',',','.').'</th></tr>';
        echo '<tr><th></th><th></th><th></th><th>IVA</th><th style="text-align: right">'.$orden_venta->getIVA().'</th></tr>';

        echo '<tr><th></th><th></th><th></th><th>Total</th><th style="text-align: right">'.$orden_venta->getValorTotal().'</th></tr>';
        ?>
        </tbody>
          </table>
           </td>
    </tr>
  </tbody>
</table>
