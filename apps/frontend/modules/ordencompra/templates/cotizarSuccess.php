    <div class="left-side-col last">
<div class="msidebar rounded">
  <div style="padding: 3px 4px 15px 14px">
    <div id="filter_settings">
      <h2>Insumo</h2>
      <div id="filter_settings_body">
            <form name="form" method="post" action="cotizar">

        <select name="insumo" onchange="this.form.submit()">
            <option value="">-- Seleccione --</option>
            <?php foreach ($todos as $proveedor_insumo): ?>
            if($
                <option value="<?php echo $proveedor_insumo->getId() ?>"><?php echo $proveedor_insumo->getInsumo()->getNombreCompleto() ?></option>
            <?php endforeach; ?>
            </select>
        </form>
      </div>
    </div>
  </div>

  <div class="cut">&nbsp;</div>
</div>
</div>
<div class="main-col-w">
    <div class="main-col">

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
                        <td><?php echo '$'.$formato->format($proveedor_insumo->getPrecio(), 'd', 'CLP') ?></td>
                        <?php if($proveedor_insumo->getProveedor()->getVentasEmail()):?>
                            <td width="300px">
                            
                                <?php echo link_to('Cotizar', 'ordencompra/cotiza?id=' . $proveedor_insumo->getId(), array('confirm' => '¿Está seguro de que desea enviar el siguiente mensaje?

Estimado ' . $proveedor_insumo->getProveedor()->getVentasNombre() . ',

Me dirijo a Ud. con motivo de cotizar el insumo ' . $proveedor_insumo->getInsumo()->getNombre() . " " . $proveedor_insumo->getInsumo()->getPresentacion() . $proveedor_insumo->getInsumo()->getUnidad() . '.

De antemano muchísimas gracias por su pronta respuesta.

Atentamente,
'.$sf_user->getGuardUser()->getName().'
Quesos Artisan')) ?>    <?php else:?>
                            <td class="panel-critical dont-hide" width="300px">
                                <div >El Proveedor no tiene email de contacto</div>
                        <?php endif;?>
                        </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>

