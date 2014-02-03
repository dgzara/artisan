<h1>Detalles del Producto de la Competencia</h1>
<table class="one-table">
  <tbody>
    <tr>
      <th>Marca:</th>
      <td><?php echo $producto_competencia->getMarca()->getNombre() ?></td>
    </tr>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $producto_competencia->getNombre() ?></td>
    </tr>
    <tr>
      <th>Peso/Volumen:</th>
      <td><?php echo $producto_competencia->getPresentacion() ?></td>
    </tr>

    <tr>
      <th>Unidades:</th>
      <td><?php echo $producto_competencia->getUnidad() ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Movil_ProductosCompetencia_EditarCompetencias")):?>
    <span class="modificar"><a href="<?php echo url_for('productos_competencia/edit?id='.$producto_competencia->getId()) ?>">Modificar</a></span>
    &nbsp;-&nbsp;
<?php endif;?>
<span class="volver"><a href="<?php echo url_for('productos_competencia/index') ?>">Volver</a></span>
</div>