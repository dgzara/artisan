<h1>Formulario de Órdenes de Compra</h1>
        
<?php 

include_partial('grupo', array('ordenes' => $ordenes, 'ordenes2'=>$ordenes2, 'boton_guardar' => 'Validar', 'accion' => 'validategrupal', 'formsCostos' => $formsCostos, 'area_de_costos_id' => $area_de_costos_id, 'centro_de_costos_id' => $centro_de_costos_id));

?>

