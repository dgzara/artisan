<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('plantilla_pauta/cargarInstruccion') ?>" method=get" id="form_instrucciones"></form>
<form action="<?php echo url_for('plantilla_pauta/cargarEtapa') ?>" method=get" id="form_etapas"></form>
<form action="<?php echo url_for('plantilla_pauta/cargarIngrediente') ?>" method=get" id="form_ingredientes"></form>

<form action="<?php echo url_for('plantilla_pauta/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

    <table class="one-table">
      <tbody>
          <?php echo $form ?>
      </tbody>
    </table>
    <br>

    <h2>Ingredientes</h2>
    <table class ="one-table" id="ingredientes">
      <thead>
          <th>Orden</th>
          <th>Nombre</th>
          <th>Cantidad Requerida</th>
          <th width="15%">Eliminar</th>
      </thead>
      <tbody>
          <?php for($i=0; $i < count($form_ingredientes); $i++): ?>
          <tr class="ingrediente" id="ingrediente_<?php echo $i?>">
              <td><?php echo $i+1;?></td>
              <?php foreach($form_ingredientes[$i] as $field_e): ?>
                    <?php if(!$field_e->isHidden()): ?>
                        <td>
                            <?php echo $field_e->render();?>
                        </td>
                   <?php else:?>
                        <?php echo $field_e->render();?>
                    <?php endif;?>
                <?php endforeach;?>
               <td><a onClick="borrarFila('ingrediente_<?php echo $i?>')"><img src="/images/tools/icons/event_icons/remove.gif" border="0"> Eliminar Ingrediente</a></td>
          </tr>
          <?php endfor;?>
      </tbody>
      <tfoot>
          <tr>
            <td colspan="4">
                <div align="right">
                    <a onClick="agregarIngrediente('ingredientes')"><img src="/images/ico-add.png" border="0"> Agregar Ingrediente</a>
                </div>
            </td>
        </tr>
      </tfoot>
    </table>
    <br>
    
    <h2>Etapas</h2>
    <table class="one-table" id="etapas">
        <thead>
            <tr>
                <th width="210px">Orden</th>
                <th>Etapa</th>
                <th width="160px"><a onClick="agregarEtapa('etapas')"><img src="/images/ico-add.png" border="0"> Agregar Etapa</a></th>
            </tr>
        </thead>
        <tbody>
            <?php for($i=0; $i < count($form_etapas); $i++): ?>
                <tr class="etapa" id="etapa_<?php echo $i?>">
                    <td colspan="3">
                        <h2>Etapa Nº <?php echo $i + 1 ?></h2>
                            <table>
                                <tbody>
                                    <tr>
                                    <?php foreach($form_etapas[$i] as $field_e): ?>
                                        <?php if(!$field_e->isHidden()): ?>
                                            <td>
                                                <?php echo $field_e->render();?>
                                            </td>
                                       <?php else:?>
                                            <?php echo $field_e->render();?>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    </tr>
                                </tbody>
                            </table>

                        <table class="tickets" id="instrucciones_etapa_<?php echo $i?>">
                            <thead>
                                <tr>
                                    <th width="5%">Orden</th>
                                    <th>Instruccion</th>
                                    <th width="15%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($j=0; $j < count($form_instrucciones[$i]); $j++):?>
                                    <tr class="instruccion" id="layer_<?php echo $i?>_<?php echo $j?>">
                                        <td><?php echo $j+1?></td>
                                        <?php foreach($form_instrucciones[$i][$j] as $field_p): ?>
                                            <?php if(!$field_p->isHidden()): ?>
                                                <td>
                                                    <?php echo $field_p->render();?>
                                                </td>
                                           <?php else:?>
                                                <?php echo $field_p->render();?>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                                <td>
                                                    <?php if($j > 0):?>
                                                        <a onClick="borrarFila('layer_<?php echo $i?>_<?php echo $j?>')"><img src="/images/tools/icons/event_icons/remove.gif" border="0"> Eliminar Instrucción</a>
                                                     <?php endif;?>
                                                </td>
                                    </tr>
                                <?php endfor;?>
                            </tbody>
                            <tfoot>
                                <tr>
                                  <td colspan="4">
                                      <div align="right">
                                          <a onClick="agregarInstruccion(<?php echo $i?>, <?php echo $j?>, 'instrucciones_etapa_<?php echo $i?>')"><img src="/images/ico-add.png" border="0"> Agregar Instrucción</a>
                                      </div>
                                  </td>
                               </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>


    <div id="barra">
        &nbsp;<span class="volver"><a href="<?php echo url_for('plantilla_pauta/index') ?>">Volver</a></span>
        <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;-&nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'plantilla_pauta/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro en eliminar esta puata?')) ?></span>
            <?php endif; ?>
        &nbsp;-&nbsp;<input type="submit" value="Guardar" />
    </div>


    
</form>
