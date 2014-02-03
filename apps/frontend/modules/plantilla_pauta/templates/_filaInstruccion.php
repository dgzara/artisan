<td><?php echo $j+1?></td>
<?php foreach ($form_instrucciones[$i][$j] as $field_p): ?>
    <?php if (!$field_p->isHidden()): ?>
            <td>
                <?php echo $field_p->render(); ?>
            </td>
            <?php else: ?>
            <?php echo $field_p->render(); ?>

    <?php endif; ?>
<?php endforeach; ?>
<td>
    <?php if($j > 0):?>
        <a onClick="borrarFila('layer_<?php echo $i?>_<?php echo $j?>')"><img src="/images/tools/icons/event_icons/remove.gif" border="0"> Eliminar Instrucción</a>
     <?php endif;?>
</td>