<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $form->renderGlobalErrors();?>


<form action="<?php echo url_for('ordenventa/cambiarCliente') ?>" method="get" id="cambioCliente"></form>
<form action="<?php echo url_for('ordenventa/cambiarLocal') ?>" method="get" id="cambioLocal"></form>
<form action="<?php echo url_for('ordenventa/cargarOrdenProducto') ?>" method="get" id="form_productos_cliente"></form>

<form id="form" onsubmit="return validateForm()" action="<?php echo url_for('ordenventa/'.($form->getObject()->isNew() ? 'create' : $accion).(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

    <?php if (!$form->getObject()->isNew()): ?>
    <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
      <table class="one-table">
        <?php
            foreach($form as $field):?>
                <?php if($field->getName() == 'productos_list'):?>

                 <?php else:?>
                        <tr>
                        <?php if(!$field->isHidden()): ?>
                            <th>
                                <?php echo $field->renderLabel() ?>
                            </th>
                            <td>
                                <?php echo $field->render().$field->renderError();?>
                            </td>
                        <?php else: ?>
                            <?php echo $field->render().$field->renderError();?>
                        <?php endif;?>

                        </tr>
                        <tr>
                <?php endif; ?>
            <?php endforeach;?>
      </table>

      <br>
      <img id="loader" src="/images/loader.gif" style="vertical-align: middle; display: none" alt ="loading"/>
      <div id="productos">
          <?php if(count($orden_venta_productos)>0):
            include_partial('listaProductos', array('orden_venta_productos' => $orden_venta_productos));
          //include_partial('listaLocales', array('locales' => $locales));
        endif;?>
      </div>

      <div id="barra" >
      &nbsp;<span class="volver"><a href="<?php echo url_for('ordenventa/index') ?>">Volver</a></span>
              <?php if (!$form->getObject()->isNew()): ?>
                &nbsp;-&nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'ordenventa/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro de eliminar esta orden de venta?')) ?></span>
              <?php endif; ?>
              &nbsp;-&nbsp;<input type="submit" value="<?php echo $boton_guardar ?>" />
      </div>

  </form>