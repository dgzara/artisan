<script type="text/JavaScript" language="JavaScript">
    $(document).ready(function()
    {
              {$('#' + 'NoVerMenu').hide();}
              {$('#' + 'main-menu').hide();}
              {$('#' + 'NoVerMenu2').hide();}
    });
</script>

<?php use_helper('I18N') ?>
<div align="center">
<h2><?php echo __('Lo sentimos! No tienes los permisos para acceder a esta página.', null, 'sf_guard') ?><br><br><a href="javascript:history.go(-1)">Puedes volver a página anterior</a></h2>

<p><?php echo sfContext::getInstance()->getRequest()->getUri() ?></p>

<h3><?php echo __('Si tienes otro usuario puedes ingresar abajo.', null, 'sf_guard') ?></h3>

<?php echo get_component('sfGuardAuth', 'signin_form') ?>

<a href="javascript:history.go(-1)">Volver a página anterior</a></div>

