<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form_calidad) ?>
<?php use_javascripts_for_form($form_calidad) ?>

<form action="<?php echo url_for('captura/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
    <tfoot>
      <tr>
        <td colspan="2">
          &nbsp;<span class="volver"><a href="<?php echo url_for('captura/index') ?>">Volver</a></span>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp; - &nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'captura/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro de eliminar la captura?')) ?></span>
          <?php endif; ?>
          &nbsp; - &nbsp; <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
        <tr>
            <td>
                <?php echo $form ?>
            </td>
        </tr>
        <tr>
            <td>
                <h2>Editar Aspectos Calidad</h2>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form_calidad ?>
            </td>
        </tr>
      </tbody>
  </table>
</form>
