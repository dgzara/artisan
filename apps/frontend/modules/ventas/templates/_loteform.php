<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $form->renderGlobalErrors();?>

<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
    <?php
        foreach($form as $field):?>
            <?php if($field->getName() == 'productos_list'):?>

             <?php else:?>
                    <tr>
                    <?php if(!$field->isHidden()): ?>
                        <th style="width:25%">
                            <?php echo $field->renderLabel() ?>
                        </th>
                        <td style="width:75%">
                            <?php echo $field->render().$field->renderError();?>
                            <label id="error_label_<?php echo $field->getName();?>" style="color:red; font-weight:normal;}"></label>
                        </td>
                    <?php else: ?>
                        <?php echo $field->render().$field->renderError();?>
                    <?php endif;?>

                    </tr>
                    <tr>
            <?php endif; ?>
        <?php endforeach;?>
  </table>
