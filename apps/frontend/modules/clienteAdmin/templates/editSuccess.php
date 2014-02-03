<?php use_helper('I18N', 'Date') ?>
<?php include_partial('clienteAdmin/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Edit ClienteAdmin', array(), 'messages') ?></h1>

  <?php include_partial('clienteAdmin/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('clienteAdmin/form_header', array('cliente' => $cliente, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('clienteAdmin/form', array('cliente' => $cliente, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('clienteAdmin/form_footer', array('cliente' => $cliente, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
