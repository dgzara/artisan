<br>
<h1>Lista de Permisos</h1>
<table class="one-table">
  <thead>
    <tr>
      
      <th>Nombre</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($permisos as $permiso): ?>
    <tr>
      <td><?php echo $permiso->getDescription() ?></td>
      <!--
      <td><a href="<?php echo url_for('sfGuardPermission/edit?id='.$permiso->getId()) ?>">Modificar</a></td>
      <td><?php echo link_to('Eliminar', 'sfGuardPermission/delete?id='.$permiso->getId(), array('method' => 'delete', 'confirm' => '¿Se encuentra seguro de que desea eliminar este grupo?')) ?></td>
      -->
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
