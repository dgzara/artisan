<h1>Detalles de Local <?php echo $local->getCliente()->getName().', '.$local->getNombre()?> </h1>

<table class="one-table">
  <tbody>

    <tr>
      <th>Razón Social:</th>
      <td><?php echo $local->getCliente()->getName() ?></td>
    </tr>
    <tr>
      <th>Local:</th>
      <td><?php echo $local->getNombre() ?></td>
    </tr>
    <tr>
      <th>Teléfono:</th>
      <td><?php echo $local->getTelefono() ?></td>
    </tr>
    <tr>
      <th>Fax:</th>
      <td><?php echo $local->getFax() ?></td>
    </tr>
    <tr>
      <th>Dirección:</th>
      <td><?php echo $local->getDireccion() ?></td>
    </tr>
    <tr>
      <th>Comuna:</th>
      <td><?php echo $local->getComuna() ?></td>
    </tr>
    <tr>
      <th>Ciudad:</th>
      <td><?php echo $local->getCiudad() ?></td>
    </tr>
    <tr>
      <th>Región:</th>
      <td><?php echo $local->getRegion() ?></td>
    </tr>
    <tr>
      <th>Contacto 1:</th>
      <td><?php echo $local->getContacto_1() ?></td>
    </tr>
    <tr>
      <th>E-Mail 1:</th>
      <td><?php echo $local->getEmail_1() ?></td>
    </tr>
    <tr>
      <th>Celular 1:</th>
      <td><?php echo $local->getCel_1() ?></td>
    </tr>
    <tr>
      <th>Contacto 2:</th>
      <td><?php echo $local->getContacto_2() ?></td>
    </tr>
    <tr>
      <th>E-Mail 2:</th>
      <td><?php echo $local->getEmail_2() ?></td>
    </tr>
    <tr>
      <th>Celular 2:</th>
      <td><?php echo $local->getCel_2() ?></td>
    </tr>
  </tbody>
</table>


<div id="barra">
<?php if($sf_user->hasPermission("Ver_Administracion_Clientes_EditarLocales")):?>
<span class="modificar"><a href="<?php echo url_for('local/edit?id='.$local->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
<span class="volver"><a href="<?php echo url_for('local/index') ?>">Volver</a></span>
</div>