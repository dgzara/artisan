
<div style="width:10%; float:right">
        <form name="form" method="post" action="excel3">
            <p><input type="submit" value="Exportar a Excel"/></p>
        </form>
</div>
<h1 style="margin-top:25px; margin-bottom: 10px; width:70%; float:left">Resumen de Costos Indirectos</h1> 

<?php include_component_slot('filtroFecha2'); ?>


<!--Tabla de Costos Indirectos-->
<table class="one-table" style="margin: 20px  0px ; margin-left: 20px;  width: 30%; float:left">
    <tbody>
        <tr>
            <th style="width:45%; font-size: 14px">Costos Indirectos</th><th style="width:55%; font-size: 14px;text-align: left"></th>
        </tr>
        <?php foreach ($areas_de_costos as $area_de_costo): ?>
            <tr>
                <td style="text-align:left"><b><?php echo $area_de_costo->getNombre() ?></b></td><td style="text-align : right"><label id="CD"><?php echo $area_de_costo->getTotal($fec1, $fec2); ?></label></td>
            </tr>        
            <?php foreach ($centros_de_costos[$area_de_costo->getId()] as $centro_de_costo): ?>
                <tr>
                    <td style="text-align:right"><?php echo $centro_de_costo->getNombre() ?></td>
                    <td style="text-align : right"><?php echo $centro_de_costo->getTotal($fec1, $fec2); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <th style="width:45%; font-size: 14px">TOTAL</th>
        <th style="width:55%; font-size: 14px;text-align: right"><?php echo $costos_indirectos_total; ?></th>
    </tfoot>
</table>