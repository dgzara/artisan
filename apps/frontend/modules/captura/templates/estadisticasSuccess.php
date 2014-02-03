<form name="formm" method="post" action="estadisticas">
    <table class="one-table" style="float: left;">
        <td>Desde</td><td><input type="text" name="desde" id="filtrar_desde" readonly="true"/></td>
        <td>Hasta</td><td><input type="text" name="hasta" id="filtrar_hasta" readonly="true"/></td>
        <td><input type="submit" value="Filtrar"/></td>
    </table>
</form>
<?php 
$i = 0;
foreach($locales as $local):?>
<table class="one-table" style="float: left;">
<tbody>
      <tr>
        <td onclick="display('ver<?php echo $i ?>');">
            <h2><?php echo $local->getCliente()->getName()." ".$local->getNombre(); ?></h2>
        </td>
      </tr>
  </tbody>
</table>

<div class ="chart" id="ver<?php echo $i?>" style="width: 100%; display: none" >
    <div id="chartsDiv<?php echo $i?>" style="width: 100%;">



        <div id="chartError<?php echo $i?>" style="width: 80%; margin:10px">
        </div>
    </div>
</div>
<?php 
$i++;
endforeach; ?>

<?php
$nombresProductosLocal = array();
$numLocal = 0;
foreach($locales as $local):
    $capturas = $local->getCapturas();
    $numCapturas = 0;
    foreach($capturas as $captura):
        $nombresProductosLocal[$numLocal][$numCapturas] = $captura->getProducto()->getNombre()." ".$captura->getProducto()->getPresentacion()." ".$captura->getProducto()->getUnidad().";".$captura->getStock().";".$captura->getMermas().";".$captura->getFueraFormato().";".$captura->getFecha();
        $numCapturas++;
    endforeach;
    $numLocal++;
endforeach;


if($hayCosas){
    $nombresProductosLocal = $cosas->getRawValue();
}

?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      google.load("visualization", "1", {packages:["corechart"]});
      google.load('visualization', '1', {packages:['table']});


      var chartId = -1;
      var nombresProductosLocal = <?php echo json_encode($nombresProductosLocal) ?>;
      

      function drawCharts() {

            var productoActual = "";
            var data = "";
            var numGraficos = 0;

            if(nombresProductosLocal[chartId] != null){
                nombresProductosLocal[chartId].sort();
                for(var k = 0;k< nombresProductosLocal[chartId].length;k++){ //por cada captura del producto

                        //chartId;Nombre;Stock;Mermas;FF;Fecha
                        var split = nombresProductosLocal[chartId][k].split(";"); // hago el split por cada captura
                        productoActual = split[0];
                        data = new google.visualization.DataTable();
                        data.addColumn('date', 'Fecha');
                        data.addColumn('number', 'Stock');
                        data.addColumn('number', 'Mermas');
                        data.addColumn('number', 'FF');
                        while(productoActual == split[0]){ //mientras continue en en el mismo producto
                            var fecha = split[4].split(" ");
                            var nums = fecha[0].split("-");
                            data.addRow([new Date(nums[0], nums[1]-1, nums[2], 0, 0, 0, 0), parseInt(split[1]), parseInt(split[2]), parseInt(split[3])]);
                            k++;
                            if(k<nombresProductosLocal[chartId].length)
                                split = nombresProductosLocal[chartId][k].split(";");
                            else
                                break;
                        }
                        if(productoActual != split[0]){
                            k--;
                        }
                        createDivContenedor(chartId, numGraficos);
                        createDivLateral(chartId, numGraficos);
                        createDivChart(chartId, numGraficos);
                        var formatter_medium = new google.visualization.DateFormat({formatType: 'medium'});
                        formatter_medium.format(data,1);
                        var chart = new google.visualization.LineChart(document.getElementById("chartDiv"+chartId+numGraficos));
                        chart.draw(data,{height: 400, title: productoActual, pointSize: 3});
                        var visualization = new google.visualization.Table(document.getElementById("chartLateralDiv"+chartId+numGraficos));
                        visualization.draw(data, {showRowNumber: true, width:320});

                        numGraficos++;
               }
               numGraficos = 0;
        }
        else{
           document.getElementById("chartError"+chartId).textContent = "No hay capturas en este local";
        }
           
      }

      function display(id)
      {
            if ($('#' + id).css('display') == 'none')
            {
                var pedazos = id.split("");
                chartId = pedazos[3];
                $('#' + id).slideDown('slow', drawCharts);
            }
            else{
                $('#' + id).slideUp('slow');
            }
      }


    function createDivChart(num,id)
    {
        var divTag = document.createElement("div");
        divTag.id = "chartDiv"+num+id;
        divTag.setAttribute("style","float:right; width:70%;");
        document.getElementById("chartContenedor"+num+id).appendChild(divTag);
    }
    function createDivLateral(num,id)
    {
        var divTag = document.createElement("div");
        divTag.id = "chartLateralDiv"+num+id;
        divTag.setAttribute("style","float:left; height:400px;overflow: hidden;");
        document.getElementById("chartContenedor"+num+id).appendChild(divTag);
    }
    function createDivContenedor(num,id)
    {
        var divTag = document.createElement("div");
        divTag.id = "chartContenedor"+num+id;
        document.getElementById("chartsDiv"+num).appendChild(divTag);
    }
    function createSeparador(num,id)
    {
        var divTag = document.createElement("div");
        divTag.id = "chartSeparador"+num+id;
        divTag.setAttribute("style","height:30px;");
        document.getElementById("chartContenedor"+num+id).appendChild(divTag);
    }
    </script>



