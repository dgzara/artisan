<h1>Archivos Importados</h1>
<h3>Los siguientes archivos fueron importados correctamente:</h3>
<table>
	<thead>
		<tr>
			<th>Archivo</th>
			<th>Número Orden Venta</th>
		</tr>	
	</thead>
<?php
foreach ($nombres as $n) {
	echo '<tr><td>'.$n['nombre'].'</td>';
	echo '<td>'.$n['orden'].'</td></tr>';
}
?>
</table>