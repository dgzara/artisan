<?php use_helper('I18N') ?>


<h1 ><?php echo __('Ingreso de usuarios', null, 'sf_guard') ?></h1>
<br>

<?php echo get_partial('sfGuardAuth/signin_form', array('form' => $form)) ?>