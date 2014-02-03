<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h3>Lista de Productos</h3>
<table class="one-table">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($plan_produccion_productos as $plan_produccion_producto): ?>
            <tr>
            <?php foreach($plan_produccion_producto as $field_p): ?>
                <?php if(!$field_p->isHidden()): ?>
                    <td>
                        <?php echo $field_p->renderLabel(); ?>
                    </td>
                <?php else: ?>
                <?php endif;?>
                <?php if($field_p->getName() == 'cantidad'): ?>
                    <td>
                        <?php echo $field_p->render();?>
                    </td>
               <?php else:?>
                    <?php echo $field_p->render();?>
                <?php endif;?>
            <?php endforeach;?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>