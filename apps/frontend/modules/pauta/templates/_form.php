<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('pauta/CargarInstrucciones') ?>" method=get" id="form_instrucciones">
</form>

<form onsubmit="return runValidationsInsumosFinal()" action="<?php echo url_for('pauta/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
<img id="loader" src="/images/loader.gif" style="vertical-align: middle; display: none" alt ="loading"/>
        <div id="instrucciones"></div>

<div id="barra">
<span class="volver"><a href="<?php echo url_for('pauta/index') ?>">Volver</a></span>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp; <span class="eliminar"><?php echo link_to('Eliminar', 'pauta/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro?')) ?></span>
          <?php endif; ?>
          <input type="submit" value="Guardar" />
</div>
</form>

