<h1>Inventario de Productos <?php echo $bodega->getNombre() ?></h1>
<br>



 <?php for($i = 0; $i < count($ramas); $i++):?>
<table class="one-table">

<tbody>
     <tr>
        <td onclick="display('ver<?php echo $i ?>');">
            <h2><?php echo $ramas[$i]->getNombre() ?></h2></td>
      </tr>
  </tbody>
</table>

<div id="ver<?php echo $i ?>" style="display: none">

    <table class="one-table">
    <thead>
    <tr>
      <th width="50%">Producto</th>
      <th width="10%">Último Movimiento</th>
      <th width="10%">Total en Maduración</th>
      <th width="10%">Bodega Valdivia</th>
      <th width="10%">Total en Tránsito</th>
      <th width="10%">Bodega Santiago</th>
      <th width="10%">Total</th>
    </tr>
    </thead>
        <tbody>
           <?php for($j = 0; $j < count($inventario_productoss[$i]); $j++):?>
              <tr>
                  <td width="50%"><?php echo $inventario_productoss[$i][$j]->getProducto()->getNombreCompleto() ?></td>
                  <td width="10%"><?php echo $inventario_productoss[$i][$j]->getDateTimeObject('fecha')->format('d M Y') ?></td>
                  <td width="10%"><?php echo $inventario_productoss[$i][$j]->getProducto()->getMaduracionTotal() ?></td>
                  <td width="10%"><?php echo $inventario_productoss[$i][$j]->getProducto()->getStockTotal() - $inventario_productoss[$i][$j]->getCantidad()?></td>                  
                  <td width="10%"><?php echo $inventario_productoss[$i][$j]->getProducto()->getTransito() ?></td>
                  <td width="10%"><?php echo $inventario_productoss[$i][$j]->getCantidad() ?></td>              
                  <td width="10%" bgcolor="<?php echo $inventario_productoss[$i][$j]->getProducto()->getColor() ?>"><?php echo $inventario_productoss[$i][$j]->getProducto()->getTotal() ?></td>

              </tr>
           <?php endfor; ?>
        </tbody>
    </table>
</div>
 <?php endfor; ?>
  
<script type="text/JavaScript">
    function display(id)
    {
        if ($('#' + id).css('display') == 'none')
        {$('#' + id).slideDown('slow');}
        else{
            {$('#' + id).slideUp('slow');}
        }
    }
</script>