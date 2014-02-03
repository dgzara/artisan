<h1 >Orden de Venta N°<?php echo $orden_venta->getNumero() ?></h1>
<br>

<table class="one-table">
  <tbody>
    <tr>
      
      <th>Fecha de Emisión:</th>
      <td><?php echo $orden_venta->getDateTimeObject('fecha')->format('d M Y') ?></td>
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
    <?php if($orden_venta->getArchivoAdjunto()):?>
    <tr>
      <th>Archivo Adjunto:</th>
      <td><a href="<?php echo url_for('homepage')?>uploads/ov/<?php echo $orden_venta->getArchivoAdjunto() ?>">Descargar</a></td>
    </tr>
    <?php endif;
    
    if($orden_venta->getArchivoAdjunto2()):?>
    <tr>
      <th>Archivo Adjunto 2:</th>
      <td><a href="<?php echo url_for('homepage')?>uploads/ov/<?php echo $orden_venta->getArchivoAdjunto2() ?>">Descargar</a></td>
    </tr>
    <?php endif;
     if($orden_venta->getArchivoAdjunto3()):?>
    <tr>
      <th>Archivo Adjunto 3:</th>
      <td><a href="<?php echo url_for('homepage')?>uploads/ov/<?php echo $orden_venta->getArchivoAdjunto3() ?>">Descargar</a></td>
    </tr>
    <?php endif;?>
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

<hr />

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Editar")):?>
<span class="modificar"><a href="<?php echo url_for('ordenventa/edit?id='.$orden_venta->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
<span class="pdf"><a href="<?php echo url_for('ordenventa/Pdf?orden_venta_id='.$orden_venta->getId()); ?>">Ver en PDF</a></span>
&nbsp;-&nbsp;
<span class="volver"><a href="<?php echo url_for('ordenventa/index') ?>">Volver</a></span>
</div>
