<td><?php echo $i+1;?></td>
<?php foreach($form_descriptor as $field_e): ?>
    <?php if(!$field_e->isHidden()): ?>
        <td>
            <?php echo $field_e->render();?>
        </td>
   <?php else:?>
        <?php echo $field_e->render();?>
    <?php endif;?>
<?php endforeach;?>
<td><a onClick="borrarFila('descriptor_<?php echo $i?>')"><img src="/images/tools/icons/event_icons/remove.gif" border="0"> Eliminar</a></td>