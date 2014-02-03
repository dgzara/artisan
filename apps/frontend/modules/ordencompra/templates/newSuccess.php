<?php use_javascript('habilitandoDisabled.js') ?>

<h1>Crear Orden de Compra</h1>
<label style="color:red"><?php if($vacio=='true') {echo '*Error: No puede ingresar una orden vacía';}?></label>
<?php include_partial('form', array('form' => $form)) ?>


