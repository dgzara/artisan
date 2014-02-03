<?php if ($selected_id != "sin_opcion_vacia"): // solución burda para dar la opción de no incluir la opción vacía?>
    <option value="" id="<?php echo $nombre?>_0">-- Seleccione --</option>
<?php endif; ?>
<?php foreach ($list as $i => $element): ?>
        <option value="<?php echo $element->getId(); ?>"
            <?php if($element->getId() == $selected_id):?>
                selected="selected"
            <?php endif;?> id="<?php echo $nombre?>_<?php echo $element->getId() ?>">
    <?php echo $element->getNombre(); ?>
    </option>
<?php endforeach ?>