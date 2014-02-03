<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $form->renderGlobalErrors();?>

<form action="<?php echo url_for('ordencompra/CargarOrdenProducto') ?>" method=get" id="form_insumos_proveedor">
</form>
<form id="form" onsubmit="return validateForm()" action="<?php echo url_for('ordencompra/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?> "method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>">

        <?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
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
                    <tr>
            <?php endif; ?>
        <?php endforeach;?>
  </table>

  <br>

  <img id="loader" src="/images/loader.gif" style="vertical-align: middle; display: none" alt ="loading"/>
  <div id="productos"></div>
  <div id="barra">
  &nbsp;<span class="volver"><a href="<?php echo url_for('ordencompra/index') ?>">Volver</a></span>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;-&nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'ordencompra/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro de eliminar esta Orden de Compra?')) ?></span>
          <?php endif; ?>
          &nbsp;-&nbsp;&nbsp;<input type="submit" value="Guardar" />
  </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        cargarProductosPorProveedor();
    });
</script>
