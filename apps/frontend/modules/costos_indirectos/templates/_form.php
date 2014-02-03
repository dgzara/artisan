<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('costos_indirectos/cambiarAreaDeCosto') ?>" method="get" id="cambioArea">
</form>

<form action="<?php echo url_for('costos_indirectos/cambiarCentroDeCosto') ?>" method="get" id="cambioCentro">
</form>

<form action="<?php echo url_for('costos_indirectos/cambiarMonto') ?>" method="get" id="cambioMonto">
</form>

<form action="<?php echo url_for('costos_indirectos/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
    <tfoot>
      <tr>
        <td colspan="2">
            <div id="barra">
                &nbsp;<span class="volver"><a href="<?php echo url_for('costos_indirectos/index') ?>">Volver</a></span>
                <?php if (!$form->getObject()->isNew()): ?>
                  &nbsp; - &nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'costos_indirectos/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro de eliminar este costo indirecto?')) ?></span>
                <?php endif; ?>
                &nbsp; - &nbsp;<input type="submit" value="Guardar" />
            </div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
</form>


<script type="text/javascript">
    $(document).ready(function() {
       document.getElementById('costos_indirectos_descripcion').setAttribute('disabled', 'disabled');
        cambiarAreaDeCosto(<?php echo $area_de_costos_id ?>, <?php echo $centro_de_costos_id?>);
       document.getElementById('costos_indirectos_centro_de_costos_id').setAttribute('onchange', 'cambiarMonto()');
       document.getElementById('costos_indirectos_area_de_costos_id').setAttribute('onchange', 'cambiarCentroDeCosto()');
    });
</script>
