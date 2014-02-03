<h1 >Detalles del Plan de Producción N°<?php echo $plan_produccion->getId() ?></h1>



<table class="one-table" >
  <tbody>
    <tr>
      <th>Fecha:</th>
      <td><?php echo $plan_produccion->getDateTimeObject('fecha')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Comentarios:</th>
      <td><?php echo $plan_produccion->getComentarios() ?></td>
    </tr>

    <tr>
      <th>Productos:</th>
      <td><table  >
        <thead>
            <tr>
      <th>Producto</th>
      <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $productos = $plan_produccion->getProductos();
        $unidades =0;
        foreach($productos as $producto){
            echo '<tr><td>'.$producto->getNombre().' '.$producto->getPresentacion().$producto->getUnidad().'</td><td>'.$producto->getCantidad().'</td></tr>';
            $unidades+=$producto->getCantidad();
        }
        echo '<tr><th>Total</th><th>'.$unidades.'</th></tr>';
        ?>
        </tbody>
          </table>
           </td>
    </tr>
  </tbody>
</table>

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Produccion_PlanDeProduccion_Editar")):?>
    <span class="modificar"><a href="<?php echo url_for('planproduccion/edit?id='.$plan_produccion->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
<span class="pdf"><a href="<?php echo url_for('planproduccion/Pdf?plan_produccion_id='.$plan_produccion->getId()); ?>">Ver en PDF</a></span>
&nbsp;-&nbsp;
<span class="volver"><a href="<?php echo url_for('planproduccion/index') ?>">Volver</a></span>
</div>