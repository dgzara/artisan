<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('marca/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
    <tfoot>
      <tr>
        <td colspan="2">
            <div id="barra">
                &nbsp;<span class="volver"><a href="<?php echo url_for('marca/index') ?>">Volver</a></span>
                <?php if (!$form->getObject()->isNew()): ?>
                    &nbsp; - &nbsp; <span class="eliminar"><?php echo link_to('Eliminar', 'marca/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro que desea eliminar esta marca?')) ?></span>
                <?php endif; ?>
                &nbsp;-&nbsp;<input type="submit" value="Guardar" />
            </div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
</form>
