<div class="main-col-w">
    <div class="main-col"><h1><?php echo $titulo ?></h1>
        <table class="one-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th>Total Neto</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orden_ventas as $orden_venta): ?>
                    <tr>
                        <td><a href="<?php echo url_for('ordenventa/show?id=' . $orden_venta->getId()) ?>" target="_blank"><?php echo $orden_venta->getNumero() ?></a></td>
                        <td><?php echo $orden_venta->getDateTimeObject('fecha')->format('d M Y') ?></td>
                        <td><a href="<?php echo url_for('cliente/show?id=' . $orden_venta->getClienteId()) ?>" target="_blank"><?php echo $orden_venta->getCliente()->getName() ?></a></td>
                        <td><?php
                    $productos = $orden_venta->getProductos();
                    echo '<ul>';
                    $total = 0;
                    $first = true;
                    foreach ($productos as $producto) {
                        echo '<li><a href="'. url_for('producto/show?id=' . $producto->getId()). '" target="_blank">' . $producto->getNombreCompleto(). '</a> (' . $producto->getCantidad() . ')</li>';
                        $total+=$producto->getPrecio() * $producto->getCantidad();
                    }
                    echo '</ul>';
                ?></td>
                    <td>$ <?php echo number_format($total,'0',',','.') ?></td>
                    <td><a href="<?php echo url_for('ordenventa/' . $link . '?id=' . $orden_venta->getId()) ?>"><?php echo $accion ?></a></td>
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
            <form id="formHerramientas" name="form" method="post" action="filter">
                Acción:
                <p><select name="accion" onchange="this.form.submit()">
                    <option value="">-- Seleccione --</option>
                    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Lista_Validar")):?>
                        <option value="Validar">Validar</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Lista_Despachar")):?>
                        <option value="Despachar">Despachar</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Lista_Recepcion")):?>
                        <option value="Registrar Recepción">Registrar Recepción</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Lista_Cobrar")):?>
                            <option value="Cobrar">Cobrar</option>
                    <?php endif;?>
                    <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Lista_Devolucion")):?>
                            <option value="Registrar Devolución">Registrar Devolución</option>
                    <?php endif;?>
                </select></p>
            </form>
      </div>
    </div>
  </div>

  <div class="cut">&nbsp;</div>
</div>
</div>
