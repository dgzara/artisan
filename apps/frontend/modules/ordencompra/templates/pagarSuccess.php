<?php use_javascript('habilitandoDisabled.js') ?>
<h1>Pagar Orden de Compra</h1>

<?php include_partial('pagarform', array('form' => $form, 'formCosto' => $formCosto, 'orden_compra_insumos' => $orden_compra_insumos, 'area_de_costos_id' => $area_de_costos_id, 'centro_de_costos_id' => $centro_de_costos_id)) ?>