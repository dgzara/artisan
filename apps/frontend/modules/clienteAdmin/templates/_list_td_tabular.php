<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo link_to($cliente->getId(), 'cliente_edit', $cliente) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo $cliente->getName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_rut">
  <?php echo $cliente->getRut() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_telefono">
  <?php echo $cliente->getTelefono() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_fax">
  <?php echo $cliente->getFax() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_direccion">
  <?php echo $cliente->getDireccion() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_ciudad">
  <?php echo $cliente->getCiudad() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_contacto">
  <?php echo $cliente->getContacto() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_email">
  <?php echo $cliente->getEmail() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_cel">
  <?php echo $cliente->getCel() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_created_at">
  <?php echo false !== strtotime($cliente->getCreatedAt()) ? format_date($cliente->getCreatedAt(), "f") : '&nbsp;' ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($cliente->getUpdatedAt()) ? format_date($cliente->getUpdatedAt(), "f") : '&nbsp;' ?>
</td>
