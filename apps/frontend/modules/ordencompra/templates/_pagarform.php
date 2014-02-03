<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($formCosto) ?>
<?php use_javascripts_for_form($formCosto) ?>
<?php $form->renderGlobalErrors();?>

<form action="<?php echo url_for('ordencompra/CargarOrdenProducto') ?>" method="get" id="form_insumos_proveedor">
</form>
<form action="<?php echo url_for('costos_indirectos/cambiarAreaDeCosto') ?>" method="get" id="cambioArea">
</form>
<form action="<?php echo url_for('costos_indirectos/cambiarCentroDeCosto') ?>" method="get" id="cambioCentro">
</form>
<form id="form" action="<?php echo url_for('ordencompra/'.($form->getObject()->isNew() ? 'create' : 'pay').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
    <?php
        foreach($form as $field):?>
            <?php if($field->getName() == 'insumos_list'):?>

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
  <div id="productos"><?php if(count($orden_compra_insumos)>0):
        include_partial('listaInsumos', array('orden_compra_insumos' => $orden_compra_insumos));
    endif;?></div>
  <div >
  </div>
<br></br>
<h1>Nuevo Costo Indirecto</h1>
Debe agregar el nuevo costo indirecto asociado a este pago.<br></br>


<input type="checkbox" class="checkbox1" value="noingresar"> No necesito ingresar costo indirecto para esta orden.
<?php if (!$formCosto->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table one-table2">
    <tfoot>
      <tr>
        <td colspan="2">

        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $formCosto; ?>
    </tbody>
  </table>
  <div >
    <input type="hidden" class="existe_costo_indirecto" name="existe_costo_indirecto" value="1" />
  &nbsp;<a href="<?php echo url_for('ordencompra/index') ?>">Volver</a>

          <input class="accionar" type="submit" value="Pagar" />
  </div>
</form>



<script type="text/javascript">
    $(document).ready(function() {
       cambiarAreaDeCosto(<?php echo $area_de_costos_id ?>, <?php echo $centro_de_costos_id?>);
       document.getElementById('costos_indirectos_centro_de_costos_id').setAttribute('onchange', 'cambiarMonto()');
       document.getElementById('costos_indirectos_area_de_costos_id').setAttribute('onchange', 'cambiarCentroDeCosto()');

       $('.checkbox1').change(function(){
          $('.one-table2').toggle();
          if($(".checkbox1").is(':checked'))
           {
            $('.existe_costo_indirecto').val('0');
           }
           else
           {
            $('.existe_costo_indirecto').val('1');
           }
       });
    });
</script>