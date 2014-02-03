<h1>Orden de Compra N°<?php echo $orden_compra->getNumero() ?></h1>

<table class="one-table">
  <tbody>
    <tr>
      <th>Fecha de Emisión:</th>
      <td><?php echo $orden_compra->getDateTimeObject('fecha')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Proveedor:</th>
      <td><?php echo $orden_compra->getProveedor()->getEmpresaNombre() ?></td>
    </tr>
    <tr>
      <th>Condiciones Comerciales y Especificaciones Técnicas:</th>
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
    <?php if($orden_compra->getArchivoAdjunto()):?>
    <tr>
      <th>Archivo Adjunto:</th>
      <td><a href="<?php echo url_for('homepage')?>uploads/oc/<?php echo $orden_compra->getArchivoAdjunto() ?>">Descargar</a></td>
    </tr>
    <?php endif;
    
    if($orden_compra->getArchivoAdjunto2()):?>
    <tr>
      <th>Archivo Adjunto 2:</th>
      <td><a href="<?php echo url_for('homepage')?>uploads/oc/<?php echo $orden_compra->getArchivoAdjunto2() ?>">Descargar</a></td>
    </tr>
    <?php endif;
     if($orden_compra->getArchivoAdjunto3()):?>
    <tr>
      <th>Archivo Adjunto 3:</th>
      <td><a href="<?php echo url_for('homepage')?>uploads/oc/<?php echo $orden_compra->getArchivoAdjunto3() ?>">Descargar</a></td>
    </tr>
    <?php endif;?>
    <tr>
      <th>Insumos:</th>
      <td><table class="one-table">
        <thead>
            <tr>
      <th>Insumo</th>
      <th>Detalle</th>
      <th>Cantidad</th>
      <th>Precio</th>
      <th>Valor Neto</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $insumos = $orden_compra->getInsumos();
        foreach($insumos as $insumo){
            echo '<tr><td>'.$insumo->getNombre().' '.$insumo->getPresentacion().$insumo->getUnidad().'</td><td>'.$insumo->getDetalle().'</td><td>'.$insumo->getCantidad().'</td><td style="text-align:right">$'.$formato->format($insumo->getPrecio(),'d','CLP').'</td><td style="text-align:right">$'.$formato->format($insumo->getPrecio()*$insumo->getCantidad(),'d','CLP').'</td></tr>';
        }
        echo '<tr><th></th><th></th><th></th><th>Neto</th><th style="text-align:right">$'.$formato->format($orden_compra->getNeto(),'d','CLP').'</th></tr>';
        echo '<tr><th></th><th></th><th></th><th>IVA</th><th style="text-align:right">$'.$formato->format($orden_compra->getIVA(),'d','CLP').'</th></tr>';
        echo '<tr><th></th><th></th><th></th><th>Total</th><th style="text-align:right">$'.$formato->format($orden_compra->getTotal(),'d','CLP').'</th></tr>';
        ?>
        </tbody>
          </table>
           </td>
    </tr>
  </tbody>
</table>


<div id="barra">
<?php if($sf_user->hasPermission("Ver_Adquisiciones_OrdenesDeCompra_EditarLista")):?>
    <span class="modificar"><a href="<?php echo url_for('ordencompra/edit?id='.$orden_compra->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
    <span class="pdf"><a href="<?php echo url_for('ordencompra/Pdf?orden_compra_id='.$orden_compra->getId()); ?>">Ver en PDF</a></span>
&nbsp;-&nbsp;
    <span class="volver"><a href="<?php echo url_for('ordencompra/index') ?>">Volver</a></span>
</div>