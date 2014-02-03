<div style="width:10%; float:right">
        <form name="form" method="post" action="excel5">
            <p><input type="submit" value="Exportar a Excel"/></p>
        </form>
</div>
<h1 style="margin-top:25px; margin-bottom: 10px; width:70%; float:left">Resumen de Insumos por Orden Compra</h1> 

<?php include_component_slot('filtroFecha5'); ?>

<h3>Sólo aparecen datos de los insumos cuyo costo en la fecha pedida es mayor que 0. <h3>
<!--Tabla de Costos Directos-->
<table class="one-table" style="margin: 20px  0px ; margin-left: 20px;  width: 30%; float:left">
    <tbody>
        <tr>
            <th style="width:45%; font-size: 14px">Costos Insumos</th><th style="width:55%; font-size: 14px;text-align: left"></th>
        </tr>
        <?php foreach ($insumos as $insumo): ?>
        <?php if($cm[$insumo->getId()]=='$ 0'){ } else{?>
            <tr>
                <td style="text-align:left"><b><?php echo $insumo->getNombre() ?></b></td><td style="text-align : right"><label id="CD"><?php echo $cm[$insumo->getId()] ?></label></td>
            </tr>
            <?php foreach ($ordenes_compra[$insumo->getId()] as $orden_compra): ?>
                <tr>
                    <td style="text-align:right"><a href="<?php echo url_for('ordencompra/show?id=' . $orden_compra->getId()) ?>">O.C. Nº <?php echo number_format($orden_compra->getNumero(), 0, ",", ".") ?></a></td>
                    <td style="text-align : right"><?php echo $orden_compra->getTotalInsumo($insumo->getId()); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php } ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <th style="width:45%; font-size: 14px">TOTAL</th>
        <th style="width:55%; font-size: 14px;text-align: right"><?php echo $costos_directos_total; ?></th>
    </tfoot>
</table>