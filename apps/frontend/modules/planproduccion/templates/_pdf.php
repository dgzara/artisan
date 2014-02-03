<h1 >Detalles del Plan de Producción N°<?php echo $plan_produccion->getId() ?></h1>



<table border="1" CELLPADDING="10" CELLSPACING="1">
  <tbody>
    <tr>
      <th width="30%">Fecha:</th>
      <td width="70%"><?php echo $plan_produccion->getDateTimeObject('fecha')->format('d M Y') ?></td>
    </tr>
    <tr>
      <th>Comentarios:</th>
      <td><?php echo $plan_produccion->getComentarios() ?></td>
    </tr>
</table><p></p>
<table border="1" CELLPADDING="10" CELLSPACING="1">
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
           