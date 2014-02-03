
<h1 >Detalles del Cliente <?php echo $cliente->getName() ?></h1>
<br>

<table class="one-table">
  <tbody>
    <!--<tr>
      <th>Id:</th>
      <td><?php echo $cliente->getId() ?></td>
    </tr>
    -->
    <tr>
      <th>Tipo:</th>
      <td><?php echo $cliente->getTipo() ?></td>
    </tr>
    <tr>
      <th>Nombre Comercial:</th>
      <td><?php echo $cliente->getName() ?></td>
    </tr>
    <tr>
      <th>Razón Social:</th>
      <td><?php echo $cliente->getRazonSocial() ?></td>
    </tr>
    <tr>
      <th>RUT:</th>
      <td><?php echo $cliente->getRut() ?></td>
    </tr>
    <tr>
      <th>Giro:</th>
      <td><?php echo $cliente->getGiro() ?></td>
    </tr>
    <tr>
      <th>Teléfono:</th>
      <td><?php echo $cliente->getTelefono() ?></td>
    </tr>
    <tr>
      <th>Fax:</th>
      <td><?php echo $cliente->getFax() ?></td>
    </tr>
    <tr>
      <th>Dirección:</th>
      <td><?php echo $cliente->getDireccion() ?></td>
    </tr>
    <tr>
      <th>Comuna:</th>
      <td><?php echo $cliente->getComuna() ?></td>
    </tr>
    <tr>
      <th>Ciudad:</th>
      <td><?php echo $cliente->getCiudad() ?></td>
    </tr>
    <tr>
      <th>Región:</th>
      <td><?php echo $cliente->getRegion() ?></td>
    </tr>
    <tr>
      <th>Casilla:</th>
      <td><?php echo $cliente->getCasilla() ?></td>
    </tr>
    <tr>
      <th>Contacto:</th>
      <td><?php echo $cliente->getContacto() ?></td>
    </tr>
    <tr>
      <th>E-Mail:</th>
      <td><?php echo $cliente->getEmail() ?></td>
    </tr>
    <tr>
      <th>Celular:</th>
      <td><?php echo $cliente->getCel() ?></td>
    </tr>
    <tr>
      <th>Locales:</th>
      <td>
          <table class="one-table">
              <tr>
              <th>Local</th>
              <th>Teléfono</th>
              <th>Fax</th>
              <th>Dirección</th>
              <th>Comuna</th>
              <th>Ciudad</th>
              <th>Región</th>
              <th>Contacto 1</th>
              <th>E-Mail 1</th>
              <th>Celular 1</th>
              <th>Contacto 2</th>
              <th>E-Mail 2</th>
              <th>Celular 2</th>
              </tr>
              <?php
        $locales = $cliente->getLocales();

        foreach ($locales as $local) {
            echo '<tr><td>'.$local->getNombre().'</td>
                <td>'.$local->getTelefono().'</td>
                    <td>'.$local->getFax().'</td>
                        <td>'.$local->getDireccion().'</td>
                            <td>'.$local->getComuna().'</td>
                            <td>'.$local->getCiudad().'</td>
                                <td>'.$local->getRegion().'</td>
                                    <td>'.$local->getContacto_1().'</td>
                                        <td>'.$local->getEmail_1().'</td>
                                           <td>'.$local->getCel_1().'</td>
                                               <td>'.$local->getContacto_2().'</td>
                                        <td>'.$local->getEmail_2().'</td>
                                           <td>'.$local->getCel_2().'</td></tr>';
        }
                ?></table></td>

    </tr>
 

  </tbody>
</table>

<hr />

<div id="barra" >
<?php if($sf_user->hasPermission("Ver_Administracion_Clientes_EditarCliente")):?>
<span class="modificar"><a href="<?php echo url_for('cliente/edit?id='.$cliente->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
<span class="volver"><a href="<?php echo url_for('cliente/index') ?>">Volver</a></span>
</div>
