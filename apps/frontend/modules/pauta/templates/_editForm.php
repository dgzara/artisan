<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('pauta/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
    <tbody>
      <?php echo $form ?>
      <?php include_partial('plantilla', array('form_instrucciones' => $form_instrucciones, 'form_lotes' => $form_lotes, 'plantilla_instrucciones' => $plantilla_instrucciones, 'plantilla_etapas' => $plantilla_etapas, 'plantillas_columnas' => $plantillas_columnas, 'form_ingredientes' => $form_ingredientes, 'plantilla_ingredientes' => $plantilla_ingredientes)) ?>
    </tbody>
    <tfoot>
        <td colspan="2">
           <div id="barra">
                <span class="volver"><a href="<?php echo url_for('pauta/index') ?>">Volver</a></span>
              <?php if (!$form->getObject()->isNew()): ?>
                &nbsp; - &nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'pauta/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro?')) ?></span>
              <?php endif; ?>
              &nbsp; - &nbsp; <input type="submit" value="Guardar" />
           </div>
        </td>
    </tfoot>
  </table>
</form>