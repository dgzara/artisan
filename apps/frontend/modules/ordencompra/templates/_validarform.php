<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $form->renderGlobalErrors();?>


<form action="<?php echo url_for('ordencompra/CargarOrdenProducto') ?>" method=get" id="form_insumos_proveedor">
</form>
<form id="form" onsubmit="return validateForm()" action="<?php echo url_for('ordencompra/'.($form->getObject()->isNew() ? 'create' : 'validate').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>


<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
    <tbody>
      <?php
        foreach($form as $field):?>
            <?php if($field->getName() == 'insumos_list'):?>
                
             <?php else:?>
                    <tr>
                    <?php if(!$field->isHidden()): ?>
                        <th>
                            <?php echo $field->renderLabel() ?>
                        </th>
                        <td>
                            <?php echo $field->render().$field->renderError();?>
                        </td>
                    <?php else: ?>
                        <?php echo $field->render().$field->renderError();?>
                    <?php endif;?>

                    </tr>

            <?php endif; ?>
        <?php endforeach;?>
    </tbody>
    <tfoot >
      <tr>
        <td colspan="2">
          
        </td>
      </tr>
    </tfoot>
  </table>
  <img id="loader" src="/images/loader.gif" style="vertical-align: middle; display: none" alt ="loading"/>
  <div id="productos"><?php include_partial('listaInsumos', array('orden_compra_insumos' => $orden_compra_insumos));?> </div>
  <div >
  &nbsp;<a href="<?php echo url_for('ordencompra/index') ?>">Volver</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Rechazar', 'ordencompra/rechazar?id='.$form->getObject()->getId(), array('confirm' => '¿Está seguro de rechazar esta Orden de Compra?')) ?>
          <?php endif; ?>
          <input type="submit" value="Validar" />
  </div>
</form>
