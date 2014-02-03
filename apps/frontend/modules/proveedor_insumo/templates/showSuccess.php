<h1>Detalle Asociación</h1>
<br>
<table class="one-table">
  <tbody>
    <tr>
      <th>Proveedor:</th>
      <td><?php echo $proveedor_insumo->getProveedor()->getEmpresaNombre() ?></td>
    </tr>
    <tr>
      <th>Insumo:</th>
      <td><?php echo $proveedor_insumo->getInsumo()->getNombre()." ".$proveedor_insumo->getInsumo()->getPresentacion().$proveedor_insumo->getInsumo()->getUnidad() ?></td>
    </tr>
    <tr>
      <th>Precio Unitario:</th>
      <td>$<?php echo $formato->format($proveedor_insumo->getPrecio(), 'd', 'CLP') ?></td>
    </tr>

  </tbody>
</table>

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Administracion_Insumos_EditarAsociaciones")):?>
    <span class="modificar"><a href="<?php echo url_for('proveedor_insumo/edit?id='.$proveedor_insumo->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
    <span class="volver"><a href="<?php echo url_for('proveedor_insumo/index') ?>">Volver</a></span>
</div>