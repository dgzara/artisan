<div class="main-col-w">
    <div class="main-col"><h1><?php echo $accion ?> Órdenes de Compra</h1>
        <table class="one-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Fecha de Emisión</th>
                    <th>Proveedor</th>
                    <th>Insumos</th>
                    <th>Total Neto</th>
                    <th><?php echo $accion ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orden_compras as $orden_compra): ?>
                    <tr>
                        <td><a href="<?php echo url_for('ordencompra/show?id=' . $orden_compra->getId()) ?>"><?php echo $orden_compra->getNumero() ?></a></td>
                        <td><?php echo $orden_compra->getDateTimeObject('fecha')->format('d M Y') ?></td>
                        <td><a href="<?php echo url_for('proveedor/show?id=' . $orden_compra->getProveedorId()) ?>" target="_blank"><?php echo $orden_compra->getProveedor()->getEmpresaNombre() ?></a></td>
                        <td><?php
                    $insumos = $orden_compra->getInsumos();
                    echo '<ul>';
                    $total = 0;
                    $first = true;
                    foreach ($insumos as $insumo) {
                        echo '<li><a href="'. url_for('insumo/show?id=' . $insumo->getId()). '" target="_blank">' . $insumo->getNombreCompleto().  '</a> (' . $insumo->getCantidad() . ')</li>';
                        $total+=$insumo->getPrecio() * $insumo->getCantidad();
                    }
                    echo '</ul>';
                ?></td>
                    <td>$ <?php echo number_format($total,'0',',','.') ?></td>
                    <td><a href="<?php echo url_for('ordencompra/' . lcfirst($accion) . '?id=' . $orden_compra->getId()) ?>"><?php echo $accion ?> Orden</a></td>
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
                    <?php if($sf_user->hasPermission("Ver_Adquisiciones_OrdenesDeCompra_Lista_Validar")):?>
                    <option value="Validar">Validar</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Adquisiciones_OrdenesDeCompra_Lista_Recepcionar")):?>
                    <option value="Recepcionar">Recepcionar</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Adquisiciones_OrdenesDeCompra_Lista_Pagar")):?>
                    <option value="Pagar">Pagar</option>
                    <?php endif;?>
                </select></p>
            </form>
      </div>
    </div>
  </div>

  <div class="cut">&nbsp;</div>
</div>
</div>
