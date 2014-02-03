<?php 
foreach ($formsCostos as $formCosto){
    {
        use_stylesheets_for_form($formCosto);
        use_javascripts_for_form($formCosto);            
    }
}
?>
<form action="<?php echo url_for('costos_indirectos/cambiarAreaDeCosto') ?>" method="get" id="cambioArea">
</form>
<form action="<?php echo url_for('costos_indirectos/cambiarCentroDeCosto') ?>" method="get" id="cambioCentro">
</form>
<form action="<?php echo url_for('ordencompra/validatecostoind') ?>" method="POST" onSubmit= "return isValid();">

<table>
    <thead>
            <tr>
                <th></th>
                <th>No necesita costo indirecto</th>
                <th>Área de Costos</th>
                <th>Centro de Costos</th>
                <th>Nombre</th>
                <th>Lugar</th>
                <th>Monto</th>
                <th>Fecha</th>
            </tr>
    </thead>
        <?php for($i=0; $i < count($formsCostos); $i++): ?>
          <tr class="costo" id="costo_<?php echo $i?>">
              <td><?php echo $i+1;?></td>
              <td><input type="checkbox" onClick="output()" class="checkbox<?php echo $i?>" value="noingresar"></td>
              <input type="hidden" class="existe_costo_indirecto_<?php echo $i?>" name="existe_costo_indirecto_<?php echo $i?>" value="1" />
              <?php foreach($formsCostos[$i] as $field_e): ?>
                    <?php if(!$field_e->isHidden()): ?>
                        <td>
                            <?php echo $field_e->render();?>
                        </td>
                   <?php else:?>
                        <?php echo $field_e->render();?>
                    <?php endif;?>
                <?php endforeach;?>
            </tr>
        <?php endfor; ?>
</table>
<input type="submit" value="<?php echo 'Agregar costo indirecto';?>">
</form>

<script type="text/javascript">
function output()
{
    for(var i=0; i< <?php echo count($formsCostos)?>; i++)
        {
            if($('.checkbox'+i).is(':checked'))
            {
                $('.existe_costo_indirecto_'+i).val(0);
            }
            else
            {
                $('.existe_costo_indirecto_'+i).val(1);
            }
        }

}
</script>

<script type="text/javascript">
    $(document).ready(function() {
        for(var i=0; i< <?php echo count($formsCostos)?>; i++)
        {
            cambiarAreaDeCosto1(<?php echo $area_de_costos_id ?>, <?php echo $centro_de_costos_id?>,i);
            document.getElementById('costo_'+i+'_centro_de_costos_id').setAttribute('onchange', 'cambiarMonto1('+i+')');
            document.getElementById('costo_'+i+'_area_de_costos_id').setAttribute('onchange', 'cambiarCentroDeCosto1(<?php echo $centro_de_costos_id?>,'+i+')');
        }
    });
</script>