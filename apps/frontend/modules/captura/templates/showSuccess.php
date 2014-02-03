<h1>Detalles de la Captura N°<?php echo $captura->getId() ?></h1>

<h2>Datos Captura</h2>
<table class="one-table">
  <tbody>
      <tr>
      <th>Modo:</th>
      <td><?php $valor = $captura->getModo(); if($valor == 1){ echo "Modo R&aacute;pido"; }else{ echo "Modo Normal";}?></td>
    </tr>
    <tr>
      <th>Producto:</th>
      <td><?php echo $captura->getProducto()->getNombre()." ".$captura->getProducto()->getPresentacion()." ".$captura->getProducto()->getUnidad() ?></td>
    </tr>
    <tr>
      <th>Local:</th>
      <td><?php echo $captura->getLocal()->getCliente()->getName().' '.$captura->getLocal()->getNombre() ?></td>
    </tr>
    <tr>
      <th>Precio:</th>
      <td>$ <?php echo $formato->format($captura->getPrecio(), 'd', 'CLP') ?></td>
    </tr>
    <tr>
      <th>Facing:</th>
      <td><?php echo $captura->getFacing() ?></td>
    </tr>
    <tr>
      <th>Fecha:</th>
      <td><?php echo $captura->getFecha() ?></td>
    </tr>
    <tr>
      <th>Stock:</th>
      <td><?php echo $captura->getStock() ?></td>
    </tr>
    <tr>
      <th>Promocion:</th>
      <td><?php echo $captura->getPromocion() ?></td>
    </tr>
    <tr>
      <th>Promotoras:</th>
      <td><?php echo $captura->getPromotoras() ?></td>
    </tr>
    <tr>
      <th>Fuera formato:</th>
      <td><?php echo $captura->getFueraFormato() ?></td>
    </tr>
    <tr>
      <th>Mermas:</th>
      <td><?php echo $captura->getMermas() ?></td>
    </tr>
    <tr>
      <th>Imagen:</th>
      <td>
          <?php if(file_exists($captura->getPhoto())):?>
            <img src="<?php echo $captura->getPhoto() ?>" width="380" height="280" />
          <?php else:?>
            No hay foto disponible.
          <?php endif;?>
      </td>
    </tr>
  </tbody>
</table>



<?php
    $aspectos = $captura->getAspectoCalidadCaptura();
?>
<h2>Aspectos de Calidad</h2>
<table class="one-table">
    <thead>
    <tr>
      <th>Aspectos de Calidad</th>
    </tr>
  </thead>
  <tbody>
      <?php foreach ($aspectos as $aspecto): ?>
    <tr>
      <th width="30%"><?php echo $aspecto->getAspectoCalidad()->getDescripcion(); ?></th>
      <td><?php $valor = $aspecto->getValor(); if($valor == 1){ echo "<b>Con Problemas</b>"; }else{ echo "Sin Problemas";}?></td>
    </tr>
        <?php endforeach; ?>
  </tbody>
</table>

<?php
    $competencias = $captura->getProductoCompetenciaCaptura();
?>
<h2>Productos de la Competencia</h2>
<table class="one-table">
    <thead>
    <tr>
      <th>Marca</th>
      <th>Nombre</th>
      <th>Presentaci&oacute;n</th>
      <th>Unidad</th>
      <th>Precio Captura</th>
    </tr>
  </thead>
  <tbody>
     <?php foreach ($competencias as $competencia): ?>
    <tr>
      <td><?php echo $competencia->getProductoCompetencia()->getMarca()->getNombre() ?></td>
      <td><?php echo $competencia->getProductoCompetencia()->getNombre() ?></td>
      <td><?php echo $competencia->getProductoCompetencia()->getPresentacion() ?></td>
      <td><?php echo $competencia->getProductoCompetencia()->getUnidad() ?></td>
      <td><?php echo $competencia->getPrecioCaptura() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Movil_Capturas_Editar")):?>
    <span class="modificar"><a href="<?php echo url_for('captura/edit?id='.$captura->getId()) ?>">Modificar</a></span>
    &nbsp;-&nbsp;
    <?php endif;?>
    <span class="volver"><a href="<?php echo url_for('captura/index') ?>">Volver</a></span>
</div>