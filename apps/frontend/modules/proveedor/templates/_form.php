<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_javascript('validandoRUT2.js') ?>

<form id="form" name="form" action="<?php echo url_for('proveedor/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">

    <tfoot>
      <tr>
        <td colspan="2">
            <div id="barra">
                &nbsp;<span class="volver"><a href="<?php echo url_for('proveedor/index') ?>">Volver</a></span>
                <?php if (!$form->getObject()->isNew()): ?>
                  &nbsp;-&nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'proveedor/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Se encuentra seguro de que desea eliminar este proveedor?')) ?></span>
                <?php endif; ?>
                &nbsp;-&nbsp;<input type="submit" value="Guardar" />
            </div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php foreach ($form as $widget): ?>
          <?php if(!strstr($widget->renderLabel(), 'proveedor__csrf_token')&&!strstr($widget->renderLabel(), 'Id')):?>
        
            <?php echo $widget->renderRow() ?>
            <?php if(strstr($widget->renderLabel(), 'Casilla Postal')):?>
        <tr><td colspan="2"><h2>Contacto Comercial</h2></td></tr>
            <?php endif;?>
            <?php if(strstr($widget->renderLabel(), 'Teléfono Contacto Comercial')):?>
        <tr><td colspan="2"><h2>Contacto Contabilidad</h2></td></tr>
            <?php endif;?>
         <?php endif;?>
        <?php if(strstr($widget->renderLabel(), 'proveedor__csrf_token')||strstr($widget->renderLabel(), 'Id')):?>
            <?php echo $widget->render() ?>
        <?php endif;?>
      <?php endforeach ?>
    </tbody>
  </table>
</form>


