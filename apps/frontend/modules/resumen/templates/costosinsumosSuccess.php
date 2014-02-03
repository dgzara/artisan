
<div style="width:10%; float:right">
        <form name="form" method="post" action="excel">
            <p><input type="submit" value="Exportar a Excel"/></p>
        </form>
</div>
<h1 style="margin-top:25px; margin-bottom: 10px; width:70%; float:left">Resumen de Costos por Insumo</h1> 

<?php include_component_slot('filtroFecha3'); ?>

<!--Tabla de Costos Elaboracion-->

<table class="one-table" style="margin: 20px  0px ; margin-left: 20px;  width: 30%; float:left">
    <tbody>
<tbody>
        <tr>
            <th style="width:45%; font-size: 14px">Costos Elaboración</th><th style="width:55%; font-size: 14px;text-align: right"><?php echo $sumaelab ?></th>
        </tr>
      <?php foreach ($ramas as $rama): ?>
            <tr>
          <td style="text-align:left"><?php echo $rama->getNombre() ?></td><td style="text-align : center"><label id="CD"><label id="CD"><?php echo $sumaramaelab[$rama->getId()]?></label></td>
      </tr> 
                <?php foreach ($productos_rama[$rama->getId()] as $producto): ?>
      <tr>
          <td style="text-align:right"><?php echo $producto->getNombre().' '.$producto->getPresentacion() ?></td><td style="text-align : center"><label id="CD"><label id="CD"><?php echo $costos_elab[$producto->getId()]?></label></td>
      </tr>
      



        <?php endforeach; ?><?php endforeach; ?>

    </tbody>
    <tfoot>
        <th style="width:45%; font-size: 14px">TOTAL</th>
        <th style="width:55%; font-size: 14px;text-align: right"><?php echo $sumaelab ?></th>
    </tfoot>
</table>


<!--Tabla de Costo Seco-->

<table class="one-table" style="margin: 20px  0px ; margin-left: 20px;  width: 30%; float:left">
    <tbody>
<tbody>
        <tr>
            <th style="width:45%; font-size: 14px">Costos Secos</th><th style="width:55%; font-size: 14px;text-align: right"><?php echo $sumaempa ?></th>
        </tr>
      <?php foreach ($ramas as $rama): ?>
            <tr>
          <td style="text-align:left"><?php echo $rama->getNombre() ?></td><td style="text-align : center"><label id="CD"><?php echo $sumaramaempa[$rama->getId()]?><label id="CD"></label></td>
      </tr> 
                <?php foreach ($productos_rama[$rama->getId()] as $producto): ?>
      <tr>
          <td style="text-align:right"><?php echo $producto->getNombre().' '.$producto->getPresentacion() ?></td><td style="text-align : center"><label id="CD"><label id="CD"><?php echo $costos_secos[$producto->getId()]?></label></td>
      </tr>
      


        <?php endforeach; ?><?php endforeach; ?>
    </tbody>
    <tfoot>
        <th style="width:45%; font-size: 14px">TOTAL</th>
        <th style="width:55%; font-size: 14px;text-align: right"><?php echo $sumaempa ?></th>
    </tfoot>
</table>


<!--Tabla de Costos Indirectos-->
<table class="one-table" style="margin: 20px  0px ; margin-left: 20px;  width: 30%; float:left">
    <tbody>
        <tr>
            <th style="width:45%; font-size: 14px">Costos Indirectos</th><th style="width:55%; font-size: 14px;text-align: left"></th>
        </tr>
        <?php foreach ($areas_de_costos as $area_de_costo): ?>
            <tr>
                <td style="text-align:left"><b><?php echo $area_de_costo->getNombre() ?></b></td><td style="text-align : right"><label id="CD"><?php echo $area_de_costo->getTotal() ?></label></td>
            </tr>        
            <?php foreach ($centros_de_costos[$area_de_costo->getId()] as $centro_de_costo): ?>
                <tr>
                    <td style="text-align:right"><?php echo $centro_de_costo->getNombre() ?></td>
                    <td style="text-align : right"><?php echo $centro_de_costo->getTotal(); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <th style="width:45%; font-size: 14px">TOTAL</th>
        <th style="width:55%; font-size: 14px;text-align: right"><?php echo $costos_indirectos_total ?></th>
    </tfoot>
</table>


<!--Tabla de Costos Directos-->
<table class="one-table" style="margin: 20px  0px ; margin-left: 20px;  width: 30%; float:left">
    <tbody>
        <tr>
            <th style="width:45%; font-size: 14px">Costos Directos</th><th style="width:55%; font-size: 14px;text-align: left"></th>
        </tr>
        <?php foreach ($insumos as $insumo): ?>
            <tr>
                <td style="text-align:left"><b><?php echo $insumo->getNombre() ?></b></td><td style="text-align : right"><label id="CD"><?php echo $insumo->getCostoMes() ?></label></td>
            </tr>
            <?php foreach ($ordenes_compra[$insumo->getId()] as $orden_compra): ?>
                <tr>
                    <td style="text-align:right"><a href="<?php echo url_for('ordencompra/show?id=' . $orden_compra->getId()) ?>">O.C. Nº <?php echo $orden_compra->getNumero() ?></a></td>
                    <td style="text-align : right"><?php echo $orden_compra->getTotalInsumo($insumo->getId()); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <th style="width:45%; font-size: 14px">TOTAL</th>
        <th style="width:55%; font-size: 14px;text-align: right"><?php echo $costos_directos_total; ?></th>
    </tfoot>
</table>
