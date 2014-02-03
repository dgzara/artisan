<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php $form->renderGlobalErrors();?>
<?php
    foreach($ramas as $rama){
        foreach($productos[$rama->getId()] as $producto){
            $plan_produccion_productos[$rama->getId()][$producto->getId()]->renderGlobalErrors();
        }
    }
?>
<form action="<?php echo url_for('planproduccion/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
    <tbody>
      <?php foreach($form as $field):?>
            <tr>
               <?php if(!$field->isHidden()): ?>
                        <th>
                            <?php echo $field->renderLabel() ?>
                        </th>
                        <td>
                            <?php echo $field->render();?>
                        </td>
                    <?php else: ?>
                        <?php echo $field->render();?>
                    <?php endif;?>
            </tr>
        <?php endforeach;?>


    <?php foreach($ramas as $rama):?>
        <table>
            <tbody>
                <tr>
                    <td onclick="display('ver<?php echo $rama->getId() ?>');">
                        <h2><?php echo $rama->getNombre() ?></h2>
                    </td>
                </tr>
            </tbody>
        </table>

        <div id="ver<?php echo $rama->getId(); ?>" style="display: none">
            <table class="one-table" id="productos<?php echo $rama->getId(); ?>">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <?php if ($form->getObject()->isNew()): ?>
                            <th><?php echo $fecha_elaboraciones[$rama->getId()]*2 ?> Días</th>
                            <th><?php echo $fecha_elaboraciones[$rama->getId()] ?> Días</th>
                            <th>Total en Maduración</th>
                            <th>Stock Valdivia</th>
                            <th>Stock Santiago</th>
                            <th>Total en Stock</th>
                            <th>Total en Tránsito</th>
                            <th>Total</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($productos[$rama->getId()] as $producto):?>
                            <tr>
                                <td>
                                    <?php echo $producto->getNombreCompleto(); ?>
                                </td>
                                <?php foreach($plan_produccion_productos[$rama->getId()][$producto->getId()] as $field_p): ?>
                                    <?php if($field_p->getName() == 'cantidad'): ?>
                                        <td>
                                            <?php echo $field_p->render();?>
                                        </td>
                                    <?php else:?>
                                        <?php echo $field_p->render();?>
                                    <?php endif;?>
                                <?php endforeach;?>
                                    <?php if ($form->getObject()->isNew()): ?>
                                        <td><?php echo $producto->getProduccion($producto->getMaduracion()*2);?></td>
                                        <td><?php echo $producto->getProduccion($producto->getMaduracion());?></td>
                                        <td><?php echo $producto->getMaduracionTotal();?></td>
                                        <td><?php echo $producto->getStockValdivia();?></td>
                                        <td><?php echo $producto->getStockSantiago();?></td>
                                        <td><?php echo $producto->getStockTotal();?></td>
                                        <td><?php echo $producto->getTransito();?></td>
                                        <td bgcolor="<?php echo $producto->getColor() ?>"><?php echo $producto->getTotal();?></td>
                                    <?php endif; ?>
                            </tr>
                 <?php endforeach; ?>
                 </tbody>
            </table>
        </div>
    <?php endforeach; ?>

    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
            <div id="barra">
          &nbsp;<span class="volver"><a href="<?php echo url_for('planproduccion/index') ?>">Volver</a></span>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;-&nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'planproduccion/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro de eliminar este plan de producción?')) ?></span>
          <?php endif; ?>
          &nbsp;-&nbsp;<input type="submit" value="Guardar" />
            </div>
        </td>
      </tr>
    </tfoot>
  </table>
</form>

<script type="text/JavaScript">
    function display(id)
    {
        if ($('#' + id).css('display') == 'none')
        {$('#' + id).slideDown('slow');}
        else{
            {$('#' + id).slideUp('slow');}
        }
    }
    
    // Vemos cuantas ramas, y seteamos las tablas para la propiedad de borrar los valores default
    var n_ramas = <?php echo count($ramas) ?>;

    for(i=1; i <= n_ramas; i++ ){
        $(':input', '#productos'+i).clearDefault();
    }
</script>