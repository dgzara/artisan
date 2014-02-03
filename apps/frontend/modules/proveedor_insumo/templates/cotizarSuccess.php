<div align="right">
<form name="form" method="post" action="cotizar">

Insumo:<select name="insumo">
<?php foreach ($todos as $proveedor_insumo): ?>
<option value="<?php echo $proveedor_insumo->getId()?>"><?php echo $proveedor_insumo->getInsumo()->getNombre()." ".$proveedor_insumo->getInsumo()->getPresentacion().$proveedor_insumo->getInsumo()->getUnidad() ?></option>
<?php endforeach; ?>
</select>
<br>
<input type="submit" value="Aceptar"/>
</form>
</div>
<h1>Cotizar Insumo</h1>
<table class="one-table">
  <thead>
     <tr>
      <th>Proveedor</th>
      <th>Precio</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($proveedor_insumos as $proveedor_insumo): ?>
    <tr>
      <td><?php echo $proveedor_insumo->getProveedor()->getEmpresaNombre() ?></td>
      <td><?php echo $proveedor_insumo->getPrecio() ?></td>
      <td><?php echo link_to('Cotizar', 'proveedor_insumo/cotiza?id='.$proveedor_insumo->getId(), array('confirm' => '¿Está seguro de que desea enviar el siguiente mensaje?

Estimado '.$proveedor_insumo->getProveedor()->getVentasNombre().',

Me dirijo a Ud. con motivo de cotizar el insumo '.$proveedor_insumo->getInsumo()->getNombre()." ".$proveedor_insumo->getInsumo()->getPresentacion().$proveedor_insumo->getInsumo()->getUnidad().'.

De antemano muchísimas gracias por su pronta respuesta.

Atentamente,
Encargado Cotizaciones
Quesos Artisan')) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<br>

<hr />

