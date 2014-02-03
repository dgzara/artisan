<div class="main-col-w">
    <div class="main-col">
        <div align="right">
            <form name="formm" method="post" action="../lote">
                <table class="one-table">
                    <td>Desde</td><td><input type="text" name="desde" id="filtrar_desde" readonly="true"/></td>
                    <td>Hasta</td><td><input type="text" name="hasta" id="filtrar_hasta" readonly="true"/></td>
                    <td>Otros campos</td><td><input type="text" value="" name="value"/></td>
                    <td><input type="submit" value="Filtrar"/></td>
                </table>

            </form>
        </div>
        <h1><?php echo $titulo ?></h1>
        <table class="one-table">
            <thead>
                <tr>
                    <th>Fecha Elaboración</th>
                    <th>N° Lote</th>
                    <th>N° Pauta</th>
                    <th>Producto</th>
                    <th>Comentarios</th>
                    <th>Cantidad Inicial</th>
                    <th>Cantidad Actual</th>
                    <?php if ($accion!="Empacar"&&$accion!="Despachar"&&$accion!="Ingresar a Maduración"): ?>
                    <th>Días en Maduración</th>
                    <?php endif ?>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lotes as $lote): ?>
                    <tr>
                        <td><?php echo $lote->getDateTimeObject('fecha_elaboracion')->format('d M Y'); ?></td>
                        <td><a href="<?php echo url_for('lote/show?id=' . $lote->getId()) ?>" target="_blank"><?php echo $lote->getNumero() ?></a></td>
                        <td><a href="<?php echo url_for('pauta/show?id=' . $lote->getPautaId()) ?>" target="_blank"><?php echo $lote->getPautaId() ?></a></td>
                        <td><a href="<?php echo url_for('producto/show?id=' . $lote->getProductoId()) ?>" target="_blank"><?php echo $lote->getProducto()->getNombreCompleto() ?></a></td>
                        <td><?php echo $lote->getComentarios() ?></td>
                        <td><?php echo $lote->getCantidad() ?></td>
                        <td><?php echo $lote->getCantidad() - $lote->getCantidad_Danada() - $lote->getCantidad_Ff() - $lote->getCc_Valdivia() - $lote->getCc_Santiago() ?></td>
                      
                        <?php if ($accion!="Empacar"&&$accion!="Despachar"&&$accion!="Ingresar a Maduración"): ?>

                        <td bgcolor="<?php echo $lote->getColor() ?>"><?php echo $lote->getRestarFechasLote() ?>/<?php echo $lote->getProducto()->getMaduracion() ?></td>

                        <?php endif ?>


                        <td><a href="<?php echo url_for('lote/' .$link. '?id=' . $lote->getId()) ?>"><?php echo $accion ?></a></td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="side-col last">
<div class="msidebar rounded">
  <div style="padding: 3px 4px 15px 14px">
    <div id="filter_settings">
      <h2>Herramientas</h2>
      <div id="filter_settings_body">
            <form name="form" method="post" action="filter">
                Acción:
                <p><select name="accion" onchange="this.form.submit()">
                    <option value="">-- Seleccione --</option>
                    <?php if($sf_user->hasPermission("Ver_Produccion_ProduccionReal_ListaLotes_Madurar")):?>
                    <option value="A Madurar">Ingresar a Maduración</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Produccion_ProduccionReal_ListaLotes_Retirar")):?>
                    <option value="Retirar">Retirar de Maduración</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Produccion_ProduccionReal_ListaLotes_Empacar")):?>
                    <option value="Empacar">Empacar</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Produccion_ProduccionReal_ListaLotes_Despachar")):?>
                    <option value="Despachar">Despachar</option>
                    <?php endif;?>
                </select></p>
            </form>
      </div>
    </div>
  </div>

  <div class="cut">&nbsp;</div>
</div>
</div>


