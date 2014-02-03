<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $usuario->getId() ?></td>
    </tr>
    <tr>
      <th>Nombre:</th>
      <td><?php echo $usuario->getNombre() ?></td>
    </tr>
    <tr>
      <th>Apellido:</th>
      <td><?php echo $usuario->getApellido() ?></td>
    </tr>
    <tr>
      <th>Email:</th>
      <td><?php echo $usuario->getEmail() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $usuario->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $usuario->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('usuario/edit?id='.$usuario->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('usuario/index') ?>">List</a>
