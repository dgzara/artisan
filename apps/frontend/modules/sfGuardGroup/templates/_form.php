<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('sfGuardGroup/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
    <table class="one-table">
    <tfoot>
      <tr>
        <td colspan="2">
            <div id="barra">
                &nbsp;<span class="volver"><a href="<?php echo url_for('guard/groups') ?>">Volver</a></span>
                <?php if (!$form->getObject()->isNew()): ?>
                    &nbsp;-&nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'sfGuardGroup/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Se encuentra seguro de que desea eliminar este grupo?')) ?></span>
                <?php endif; ?>
                &nbsp;-&nbsp;<input type="submit" name="save" value="Guardar" />
            </div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php //echo $form ?>

          <?php
        foreach($form as $field):?>
            
            <tr>
                    <?php if(!$field->isHidden()): ?>
                            
                        <th>
                            <?php echo $field->renderLabel() ?>
                        </th>
                        <td>
                            <?php echo $field->render();?>
                        </td>
                    <?php else: ?>
                        <?php echo $field->render();?>
                    <?php endif;?>

                    </tr>
                    <tr>
            
        <?php endforeach;?>


    </tbody>
  </table>
   
</form>
