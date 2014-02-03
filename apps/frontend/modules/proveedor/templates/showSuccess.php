<h1 >Detalles del Proveedor "<?php echo $proveedor->getEmpresaNombre() ?>"</h1>
<br>

<table class="one-table">
  <tbody>
    <tr>
      <th width="50%">Razón Social:</th>
      <td><?php echo $proveedor->getEmpresaNombre() ?></td>
    </tr>
    <tr>
      <th>Rut:</th>
      <td><?php echo $proveedor->getEmpresaRut() ?></td>
    </tr>
    <tr>
      <th>Giro:</th>
      <td><?php echo $proveedor->getEmpresaGiro() ?></td>
    </tr>
    <tr>
      <th>Teléfono:</th>
      <td><?php echo $proveedor->getEmpresaTelefono() ?></td>
    </tr>
    <tr>
      <th>Fax:</th>
      <td><?php echo $proveedor->getEmpresaFax() ?></td>
    </tr>
    <tr>
      <th>Dirección:</th>
      <td><?php echo $proveedor->getEmpresaDireccion() ?></td>
    </tr>
    <tr>
      <th>Comuna:</th>
      <td><?php echo $proveedor->getEmpresaComuna() ?></td>
    </tr>
    <tr>
      <th>Región:</th>
      <td><?php echo $proveedor->getEmpresaRegion() ?></td>
    </tr>
    <tr>
      <th>Casilla Postal:</th>
      <td><?php echo $proveedor->getEmpresaCasillaPostal() ?></td>
    </tr>


  </tbody>
</table>

<h2 >Contacto Ventas</h2>


<table class="one-table">
  <tbody>
    <tr>
      <th width="50%">Nombre:</th>
      <td><?php echo $proveedor->getVentasNombre() ?></td>
    </tr>
    <tr>
      <th>E-Mail:</th>
      <td><?php echo $proveedor->getVentasEmail() ?></td>
    </tr>
    <tr>
      <th>Celular:</th>
      <td><?php echo $proveedor->getVentasCelular() ?></td>
    </tr>
    <tr>
      <th>Teléfono:</th>
      <td><?php echo $proveedor->getVentasTelefono() ?></td>
    </tr>
  </tbody>
</table>


<h2 >Contacto Contabilidad</h2>

<table class="one-table">
  <tbody>
    <tr>
      <th width="50%">Nombre:</th>
      <td><?php echo $proveedor->getContabilidadNombre() ?></td>
    </tr>
    <tr>
      <th>E-Mail:</th>
      <td><?php echo $proveedor->getContabilidadEmail() ?></td>
    </tr>
    <tr>
      <th>Celular:</th>
      <td><?php echo $proveedor->getContabilidadCelular() ?></td>
    </tr>
    <tr>
      <th>Teléfono:</th>
      <td><?php echo $proveedor->getContabilidadTelefono() ?></td>
    </tr>
  </tbody>
</table>


<hr />

<div id="barra">
<?php if($sf_user->hasPermission("Ver_Administracion_Insumos_EditarProveedores")):?>
    <span class="modificar"><a href="<?php echo url_for('proveedor/edit?id='.$proveedor->getId()) ?>">Modificar</a></span>
&nbsp;-&nbsp;
<?php endif;?>
    <span class="volver"><a href="<?php echo url_for('proveedor/index') ?>">Volver</a></span>
</div>
