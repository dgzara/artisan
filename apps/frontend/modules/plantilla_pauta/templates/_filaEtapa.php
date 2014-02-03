<td colspan="3">

    <h2>Etapa Nº <?php echo $i + 1 ?></h2>
    <table class="one-table">
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
                    <td><a onClick="borrarFila('etapa_<?php echo $i?>')"><img src="/images/tools/icons/event_icons/remove.gif" alt="Eliminar" border="0"> Eliminar Etapa</a></td>
            </tr>
        </tbody>
    </table>
    
    <table class="tickets" id="instrucciones_etapa_<?php echo $i?>">
        <thead>
            <tr>
                <th width="100px">Orden</th>
                <th>Instruccion</th>
                <th width="15%"></th>
            </tr>
        </thead>
        <tbody>
            <?php for($j=0; $j < count($form_instrucciones[$i]); $j++):?>
                <tr class="instruccion" id="layer_<?php echo $i?>_<?php echo $j?>">
                    <td><?php echo $j + 1?></td>
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