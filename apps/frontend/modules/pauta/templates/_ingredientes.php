<h2>Ingredientes</h2>
<table class="tickets">
    <thead>
         <tr>
            <th>Ingrediente</th>
            <th>Cantidad Requerida</th>
            <th>Cantidad Disponible</th>
            <th>Cantidad Utilizada</th>
        </tr>
    </thead>
    <tbody>
        <?php for($i = 0; $i < count($plantilla_ingredientes); $i++):?>
            <tr>
                <td>
                    <?php echo $plantilla_ingredientes[$i]->getInsumo()->getNombreCompleto(); ?>
                </td>
                <td>
                    <?php echo $plantilla_ingredientes[$i]->getCantidad(); ?>
                </td>
                <td id="<?php echo "cantidad_requerida_".$i; ?>">
                    <?php echo $plantilla_ingredientes[$i]->getInsumo()->getCantidadDisponible(); ?>
                </td>
                <?php foreach($form_ingredientes[$i] as $field):?>
                    <?php if(!$field->isHidden()):?>
                        <td>
                            <?php echo $field->render(); ?>
                        </td>
                    <?php else:?>
                        <?php echo $field->render(); ?>
                    <?php endif;?>
                <?php endforeach; ?>
            </tr>
        <?php endfor;?>
    </tbody>
</table>