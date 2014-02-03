<h1>Nivel de producción por Tipo de Producto (unidades)</h1>

<?php for($h = 0; $h < count($ramas); $h++):?>
<table class="one-table">

<tbody>
     <tr>
        <td onclick="display('ver<?php echo $h ?>');">
            <h2><?php echo $ramas[$h]->getNombre() ?></h2>
        </td>
      </tr>
  </tbody>
</table>

<div id="ver<?php echo $h ?>" style="display: none">
<table class="one-table">
    <tr>
        <th width="30%">Fecha</th>
        <?php foreach($fechas as $fecha):?>
            <th><?php echo date('d-m-Y', strtotime($fecha));?> </th>
        <?php endforeach;?>

    </tr>
    <?php for($i = 0; $i < count($lotes[$h]); $i++):?>
    <tr>
        <td><?php echo $lotes[$h][$i]->getProducto()->getNombreCompleto()?></td>
        <?php for($j = 0; $j < count($fechas); $j++):?>
            <td><?php echo $lotes[$h][$i]->getCantidadByFechaElaboracion($fechas[$j]) ?></td>
        <?php endfor; ?>
    </tr>
    <?php endfor;?>
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
