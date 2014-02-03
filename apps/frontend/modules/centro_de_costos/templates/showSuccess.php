<h1>Ver Centro de Costo </h1>
<table class="one-table">
  <tbody>
    <?php 
    $this->formato = new sfNumberFormat('es_CL');
    ?>
    <tr>
      <th>Nº:</th>
      <td><?php echo $centro_de_costos->getId() ?></td>
    </tr>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $centro_de_costos->getNombre() ?></td>
    </tr>
    <tr>
      <th>Area de costos:</th>
      <td><?php echo $centro_de_costos->getAreaDeCostos()->getNombre() ?></td>
    </tr>
    <tr>
      <th>Descripcion:</th>
      <td><?php echo $centro_de_costos->getDescripcion() ?></td>
    </tr>
    <tr>
      <th>Monto predeterminado:</th>
      <td><?php echo '$'.$this->formato->format($centro_de_costos->getMontoDefault(),'d','CLP') ?></td>
    </tr>
  </tbody>
</table>

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Administracion_Costos_EditarAreasDeCosto")):?>
        <span class="modificar"><a href="<?php echo url_for('centro_de_costos/edit?id='.$centro_de_costos->getId()) ?>">Modificar</a></span>
    &nbsp;-&nbsp;
    <?php endif;?>
<span class="volver"><a href="<?php echo url_for('centro_de_costos/index') ?>">Volver</a></span>
</div>