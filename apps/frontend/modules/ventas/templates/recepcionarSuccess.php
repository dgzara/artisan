<?php use_javascript('habilitandoDisabled.js') ?>

<h1 >Recepcionar Lote</h1>
<br>

<form id="form" action="<?php echo url_for('ventas/'.($form->getObject()->isNew() ? 'create' : 'recieve').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php include_partial('loteform', array('form' => $form)) ?>
  <div id="barra">
  &nbsp;<span class="volver"><a href="<?php echo url_for('ventas/lote') ?>">Volver</a></span>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;-&nbsp;<span class="rechazar"><?php echo link_to('Rechazar', 'lote/rechazar?id='.$form->getObject()->getId(), array('confirm' => '¿Está seguro de rechazar este lote?')) ?></span>
          <?php endif; ?>
          &nbsp;-&nbsp;<input type="submit" value="Recepcionar" />
  </div>
</form>