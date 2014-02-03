<h1>Modificar Lote</h1>
<br>

<form action="<?php echo url_for('lote/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php include_partial('form', array('form' => $form)) ?>
  <div id="barra">
    <span class="volver">&nbsp;<a href="<?php echo url_for('lote/index') ?>">Volver</a></span>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;-&nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'lote/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro de eliminar este lote?')) ?></span>
          <?php endif; ?>
          &nbsp;-&nbsp;<span class="modificar"><input type="submit" value="Modificar" /></span>
  </div>
</form>