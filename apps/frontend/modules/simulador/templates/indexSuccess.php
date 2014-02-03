<h1>Simulador</h1>
<h2>Costos</h2>
<?php include_component_slot('filtroFecha');?>
<table style="margin: 20px  0px" class="one-table">
<tbody>
      <tr>
        <th style="width:30%;">Nombre</th><th style="width:30%;">Valor</th><th style="width:40%;"></th>
    </tr>
      <tr>
        <td>Costos Indirectos</td><td>  <label id="CIFijo">  </label></td><td></td>
      </tr>
      <tr>
        <td>Costos Indirectos Extra</td><td> <input id="valorDifExtra" value="0"/></td> <td>Corresponde a algún costo extra que se quiera incluir en la simulación.</td>
      </tr>
      <tr>
        <td><strong>Costo Indirecto Total</strong></td><td><strong>  <label id="CITotal">  </label></strong></td><td></td>
      </tr>
      <tr>
        <td>Costos Directos</td><td><label id="CDBase"></label></td><td></td>
      </tr>
      <tr>
        <td>Costos Directos Extra</td><td><label id="CDExtra"> 0 </label> </td> <td> Delta de la producción. </td>
      </tr>
      <tr>
        <td><strong>Costo Directo Total</strong></td><td><strong><label id="CDTotal"> 0 </label></strong></td> <td>  </td>
      </tr>
      <tr>
          <td><h2>Total: </h2></td><td><h2> <label id="CTotal">  </label></h2> </td><td><!--<input type="image" height="60px" width="60px" src="/images/Play-Hot-icon.png" style="margin-left: auto; margin-right: auto; display: block;"/>--></td>
      </tr>
 </tbody>
</table>

 <?php for($i = 0; $i < count($ramas); $i++):?>
<table class="one-table">
<tbody>   
      <tr>
        <th onclick="display('ver<?php echo $i ?>');">
            <h2 style="padding-right: 40px; padding-left: 10px;"><?php echo $ramas[$i]->getNombre() ?><!--<label id='baseAn<?php echo $i ?>' style="float:right; margin-left: 10px;"></label>--><input id =alphaAn<?php echo $i ?> type="range" min="0" max="1" step="0.0001" name="alpha" style="float:right;" /><label for='An<?php echo $i ?>' style="float:right; margin-right: 10px;">alpha: </label></h2>
        </th>
      </tr>      
  </tbody>
</table>

<div class ="chart" id="ver<?php echo $i?>" style="width: 100%; display: none" >
    <div style=" float:left; width: 30%;">
        <div id="table_<?php echo $i?>"></div>
        </div>
        <div style="width: 70%; float:right;">
            <div id="data_view_<?php echo $i?>" style="width: auto; height: auto; padding: 0px 60px 20px 60px;"></div>
            <div id="chart_div_<?php echo $i?>" style="height:500px;"></div>
        </div>
</div>
<?php endfor; ?>