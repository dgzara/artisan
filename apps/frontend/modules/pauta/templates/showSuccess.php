<br>
<table class="one-table">
  <tbody>
    <tr>
      <th>Plantilla de Pauta:</th>
      <td><?php echo $pauta->getPlantillaPauta()->getNombre() ?></td>
    </tr>
    <tr>
      <th>Fecha de Elaboración:</th>
      <td><?php echo $pauta->getDateTimeObject('fecha')->format('d M Y') ?></td>
    </tr>
  </tbody>
</table>

<h2>Ingredientes</h2>
<table class="one-table">
    <thead>
        <th>Ingrediente</th>
        <th>Cantidad Requerida</th>
        <th>Cantidad Usada</th>
    </thead>
    <tbody>
        
        <?php foreach($pauta->getIngredientes() as $ingrediente):?>
        <tr>
            <td>
               <?php echo $ingrediente->getPlantillaIngrediente()->getInsumo()->getNombreCompleto(); ?>
            </td>
            <td>
               <?php echo $ingrediente->getPlantillaIngrediente()->getCantidad(); ?>
            </td>
            <td>
               <?php echo $ingrediente->getCantidadUsada(); ?>
            </td>
        </tr>
        <?php endforeach;?>
        
    </tbody>
</table>



<h2>Instrucciones</h2>
<table class="one-table">
    <thead>
        <th>Orden</th>
        <th>Instruccion</th>
        <?php foreach($plantilla_columnas as $columna):?>
            <th><?php echo $columna->getPlantillaColumna()->getNombre()?> </th>
        <?php endforeach;?>
    </thead>
    <tbody>
        <?php for($i=1; $i <= count($plantilla_etapas); $i++):?>
            <tr>
                <td colspan="4"><?php echo $plantilla_etapas[$i-1]->getNombre(); ?></td>
            </tr>
            <?php for($j=0; $j < count($plantilla_instrucciones[$i]); $j++):?>
            <tr>
                <td><?php echo $plantilla_instrucciones[$i][$j]->getOrden() ?></td>
                <td><?php echo $plantilla_instrucciones[$i][$j]->getDescripcion() ?></td>
                <?php for($k=0; $k < count($plantilla_columnas); $k++):?>
                     <td><?php echo $array_instrucciones[$i][$j+1][$k] ?></td> 
                <?php endfor;?>
            </tr>
            <?php endfor;?>
        <?php endfor;?>
    </tbody>
</table>


<h2>Lotes</h2>
<table class="one-table">
    <thead>
        <th>Lote</th>
        <th>Cantidad</th>
        <th>Comentarios</th>
    </thead>
    <tbody>
        <?php foreach($lotes as $lote):?>
            <tr>
                <td width="30%"><?php echo $lote->getProducto()->getNombreCompleto() ?></td>
                <td width="10%"><?php echo $lote->getCantidad() ?></td>
                <td><?php echo $lote->getComentarios() ?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Produccion_ProduccionReal_EditarElaboraciones")):?>
    <span class="modificar"><a href="<?php echo url_for('pauta/edit?id='.$pauta->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
    <span class="volver"><a href="<?php echo url_for('pauta/index') ?>">Volver</a></span>
</div>