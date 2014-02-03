
<div style="width:10%; float:right">
        <form name="form" method="post" action="resumen/excel2">
            <p><input type="submit" value="Exportar a Excel"/></p>
        </form>
</div>
<h1 style="margin-top:25px; margin-bottom: 10px; width:70%; float:left">Resumen Costos Totales</h1> 

<?php include_component_slot('filtroFecha'); ?>


<!--Tabla de Costos Elaboracion-->

<table class="one-table" style="margin: 20px  0px ; margin-left: 20px;  width: 30%; float:left">
    <tbody>
<tbody>
        <tr>
            <td style="width:45%; font-size: 14px">Costos Elaboración</td><td style="width:55%; font-size: 14px;text-align: right"><?php echo $sumaelab ?></td>
        </tr>
        <tr>
            <td style="width:45%; font-size: 14px">Costos Secos</td><td style="width:55%; font-size: 14px;text-align: right"><?php echo $sumaempa ?></td>
        </tr>
        <tr>
            <th style="width:45%; font-size: 14px">Costos Directos Totales</th><th style="width:55%; font-size: 14px;text-align: right;"><?php echo $costodirecto ?></th>
            
        </tr>
        <tr>
            <th style="width:45%; font-size: 14px">Costos Indirectos</th><th style="width:55%; font-size: 14px;text-align: right"><?php echo $costos_indirectos_total ?></th>
            
        </tr>
        <tr>
            <th style="width:45%; font-size: 14px">Costos Total</th><th style="width:55%; font-size: 14px;text-align: right"><?php echo $costotal ?></th>
            
        </tr>

    </tbody>
    
</table>

