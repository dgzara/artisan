<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('producto/cargarDescriptor') ?>" method=get" id="form_descriptor">
</form>

<form action="<?php echo url_for('producto/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

<table class="one-table">
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>


<h2>Descriptor</h2>
<table class ="one-table" id="descriptores">
    <thead>
        <th>Número</th>
        <th>Insumo</th>
        <th>Cantidad</th>
        <th>Detalles</th>
        <th>Acciones</th>
    </thead>
    <tbody>
        <?php for($i = 0; $i < count($form_descriptores); $i++):?>
            <tr id="descriptor_<?php echo $i?>" class="descriptor">
                <td><?php echo $i+1?></td>
                <?php foreach($form_descriptores[$i] as $field_e): ?>
                    <?php if(!$field_e->isHidden()): ?>
                        <td>
                            <?php echo $field_e->render();?>
                        </td>
                   <?php else:?>
                        <?php echo $field_e->render();?>
                    <?php endif;?>
                <?php endforeach;?>
                <td><a onClick="borrarFila('descriptor_<?php echo $i?>')"><img src="/images/tools/icons/event_icons/remove.gif" border="0"> Eliminar</a></td>
            </tr>
        <?php endfor;?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">
                <div align="right">
                    <a onClick="agregarDescriptor('descriptores')"><img src="/images/ico-add.png" border="0"> Agregar</a>
                </div>
            </td>
        </tr>
    </tfoot>
</table>

<div id="barra">

    <span class="volver"><a href="<?php echo url_for('producto/index') ?>">Volver</a></span>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;-&nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'producto/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Se encuentra seguro de que desea eliminar este producto?')) ?></span>
          <?php endif; ?>
    &nbsp;-&nbsp;<input type="submit" value="Guardar" />
</div>

</form>