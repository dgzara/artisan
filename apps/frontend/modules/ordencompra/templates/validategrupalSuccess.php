<h1>Órdenes de Compra</h1>
<br>

<?php foreach($mensajes as $mensaje){
	echo $mensaje;
	?><br>
<?php
}
?><p><?php echo $ms;?></p>

<?php
if($est=="Pagar")
{
	?>
	<h2>Datos</h2>
	<?php foreach($datosordenes as $mensaje){
	echo $mensaje;
	?>
	<br>
	<?php }
	include_partial('grupo2', array('boton_guardar' => 'Ingresar Costos', 'accion' => 'validatecostoind', 'formsCostos' => $formsCostos, 'area_de_costos_id' => $area_de_costos_id, 'centro_de_costos_id' => $centro_de_costos_id));
}
?>