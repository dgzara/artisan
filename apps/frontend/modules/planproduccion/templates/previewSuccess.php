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

