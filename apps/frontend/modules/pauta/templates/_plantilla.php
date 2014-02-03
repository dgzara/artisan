<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php include_partial('ingredientes', array('form_ingredientes' => $form_ingredientes, 'plantilla_ingredientes' => $plantilla_ingredientes)) ?>

<h2>Instrucciones</h2>
<table class="tickets">
    <thead>
         <tr>
            <th width="5%">Orden</th>
            <th>Instruccion</th>
            <?php foreach($plantillas_columnas as $plantillas_columna):?>
                <th><?php echo $plantillas_columna->getPlantillaColumna()->getNombre() ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php for($i=0 ; $i < count($plantilla_etapas); $i++):?>
        <tr>
            <td colspan="4"><h3><?php echo $plantilla_etapas[$i]->getNombre();?></h3>
            </td>
        </tr>
            <?php for($j=0; $j < count($form_instrucciones[$i]); $j++):?>
                <tr>
                    <td><?php echo $plantilla_instrucciones[$i][$j]->getOrden(); ?></td>
                    <td><?php echo $plantilla_instrucciones[$i][$j]->getDescripcion()?></td>
                    <?php for($k= 0; $k < count($plantillas_columnas); $k++):?>
                        <?php foreach($form_instrucciones[$i][$j][$k] as $field): ?>
                        <?php if(!$field->isHidden()):?>
                            <td><?php echo $field->render();?></td>
                        <?php else:?>
                                <?php echo $field->render();?>
                        <?php endif;?>
                    <?php endforeach;?>
                    <?php endfor; ?>
                </tr>
            <?php endfor;?>
        <?php endfor;?>
    </tbody>
</table>

<?php include_partial('lotes', array('form_lotes' => $form_lotes)) ?>
