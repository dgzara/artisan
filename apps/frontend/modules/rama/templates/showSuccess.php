<h1>Detalles de la Áreas de Negocio "<?php echo $rama->getNombre() ?>"</h1>
<br>

<table class="one-table">
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $rama->getId() ?></td>
    </tr>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $rama->getNombre() ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Administracion_ProductosEnVenta_EditarAreasdeNegocio")):?>
    <span class="modificar"><a href="<?php echo url_for('rama/edit?id='.$rama->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
    <span class="volver"><a href="<?php echo url_for('rama/index') ?>">Volver</a></span>
</div>

