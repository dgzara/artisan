<?php use_javascript('habilitandoDisabled.js') ?>
<h1 >Retirar Lote de Maduración</h1>
<br>

<form id="form" action="<?php echo url_for('lote/'.($form->getObject()->isNew() ? 'create' : 'retrieve').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php include_partial('form', array('form' => $form)) ?>
  <div id="barra" >
  &nbsp;<span class="volver"><a href="<?php echo url_for('lote/index') ?>">Volver</a></span>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;-&nbsp;<span class="rechazar"><?php echo link_to('Rechazar', 'lote/rechazar?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro de rechazar este Lote?')) ?></span>
          <?php endif; ?>
          &nbsp;-&nbsp;<input type="submit" value="Retirar" />
  </div>
</form>