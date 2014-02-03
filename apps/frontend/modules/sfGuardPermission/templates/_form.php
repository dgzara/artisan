<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('sfGuardPermission/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
<table class="one-table">
    <tfoot>
      <tr>
        <td  colspan="2">
          &nbsp;<br><a href="<?php echo url_for('guard/permissions') ?>">Volver a lista</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Eliminar', 'sfGuardPermission/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Se encuentra seguro de que desea eliminar este permiso?')) ?>
          <?php endif; ?>
          <input type="submit" name="save" value="Guardar" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
   
</form>
