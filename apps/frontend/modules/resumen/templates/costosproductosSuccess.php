
<div style="width:10%; float:right">
        <form name="form" method="post" action="excel4">
            <p><input type="submit" value="Exportar a Excel"/></p>
        </form>
</div>
<h1 style="margin-top:25px; margin-bottom: 10px; width:70%; float:left">Resumen de Costos por Producto</h1> 

<?php include_component_slot('filtroFecha4'); ?>

<!--Tabla de Costos Elaboracion-->
<table class="one-table" style="margin: 20px  0px ; margin-left: 40px;  width: 70%; float:left">
    <tbody>
<tbody>
        <tr>
            <th style="width:150px; font-size: 14px;text-align: center">Producto</th>
            <th style="width:150px; font-size: 14px;text-align: center">Costos Elaboración [$]</th>
            <th style="width:150px; font-size: 14px;text-align: center">Costo Seco [$]</th>
            <th style="width:150px; font-size: 14px;text-align: center">Costo Directo Total [$]</th>
            <th style="width:150px; font-size: 14px;text-align: center">C. Marginal [$/u]</th>
        </tr>
      <?php foreach ($ramas as $rama): ?>
            <tr>
          <td style="text-align:left"><?php echo $rama->getNombre() ?></td>
          <td style="text-align : center"><label id="CD"><label id="CD"><?php echo $sumaramaelab[$rama->getId()]?></label></td>
          <td style="text-align : center"><label id="CD"><?php echo $sumaramaempa[$rama->getId()]?><label id="CD"></label></td>
          <td style="text-align : center"><label id="CD"><label id="CD"><?php echo $totalrama[$rama->getId()]?></label></td>
          <td style="text-align : center"><label id="CD"><label id="CD"><?php ?></label></td>
      </tr> 
                <?php foreach ($productos_rama[$rama->getId()] as $producto): ?>
      <tr>
          <td style="text-align:right"><?php echo $producto->getNombre().' '.$producto->getPresentacion() ?></td>
          <td style="text-align : center"><label id="CD"><label id="CD"><?php echo $costos_elab[$producto->getId()];?></label></td>
          <td style="text-align : center"><label id="CD"><label id="CD"><?php echo $costos_secos[$producto->getId()];?></label></td>
          <td style="text-align : center"><label id="CD"><label id="CD"><?php echo $totalproducto[$producto->getId()]?></label></td>
          <td style="text-align : center"><label id="CD"><label id="CD"><?php echo $costos_uni[$producto->getId()]?></label></td>

          </tr>

        <?php endforeach; ?><?php endforeach; ?>

    </tbody>
    <tfoot>
        <th style="font-size: 14px">TOTAL</th>
        <th style="font-size: 14px;text-align: right"><?php $tot3 = $sumaelab; $tot4 = number_format($tot3, 0, ",", "."); echo '$'.$tot4 ?></th>
        <th style="font-size: 14px;text-align: right"><?php $tot3 = $sumaempa; $tot4 = number_format($tot3, 0, ",", "."); echo '$'.$tot4 ?></th>
        <th style="font-size: 14px;text-align: right"><?php $tot3 = ($sumaempa+$sumaelab); $tot4 = number_format($tot3, 0, ",", "."); echo '$'.$tot4 ?></th>
        <th style="font-size: 14px;text-align: right"><?php ?></th>
    </tfoot>
</table>
