<?php foreach($ordenes_venta as $v):?>
			<tr>
                <?php
            $estado_accion = $v->getAccion();
          if($v->getAccion() == 'Despachar') {
            $link = 'ordenventa/despachar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Despachar</a>";
          }
          elseif ($v->getAccion()=='Validar') {
            $link = 'ordenventa/validar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Validar</a>";
          }
          elseif ($v->getAccion()=='Cobrar') {
            $link = 'ordenventa/cobrar/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Cobrar</a>";
          }
          elseif ($v->getAccion()=='Registrar Recepción') {
            $link = 'ordenventa/recepcion/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Registrar Recepción</a>";
          }
          elseif ($v->getAccion()=='Registrar Devolución') {
            $link = 'ordenventa/devolucion/id/'.$v->getId();
            $estado_accion = " <a href='".$link."'>Registrar Devolución</a>";
          } ?>
                        	<td><?php echo $v->getNumero()?></td>
                        	<td><?php echo $v->getDateTimeObject('fecha')->format('d-m-Y')?></td>
                        	<td><?php echo $v->getCliente()->getName()?></td>
                        	<td>
                                <?php
                                echo $v->getNOc()
                                    // $productos = $v->getProductos();
                                    // echo '<ul>';
                                    // $total = 0;
                                    // $first = true;
                                    // foreach ($productos as $producto) {
                                    //     echo '<li><a href="'. url_for('producto/show?id=' . $producto->getId()). '" target="_blank">' . $producto->getNombreCompleto(). '</a> (' . $producto->getCantidad() . ')</li>';
                                    //     $total+=$producto->getPrecio() * $producto->getCantidad();
                                    // }
                                    // echo '</ul>';
                                ?>
                            </td>
                        	<td><?php echo $v->getLocal()->getNombre()?></td>
                        	<td><?php echo $v->getValorNeto()?></td>
                            <td class='estado'><?php echo $estado_accion?></td>
                        	<td>
                                <?php
                                  if($v->getAccion()!='Validar'){
                                    $deshacer = '<a href="ordenventa/deshacer?id='.$v->getId().'"><img src="/web/images/tools/icons/event_icons/ico-undo.png" border="0"></a>';
                                    echo $deshacer;
                                  }
                                ?>
                            </td>
                        	<td><a class="jt" rel="ordenventa/preview/<?php echo $v->getId()?> " title="Orden Venta <?php echo $v->getNumero()?>"  href="<?php echo url_for('ordenventa/show?id='.$v->getId()) ?>"><img src="/web/images/tools/icons/event_icons/ico-story.png" border="0"></a></td>
                        	<?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Editar")):?>
                            		<td><a href="<?php echo url_for('ordenventa/edit?id='.$v->getId()) ?>"><img src="/web/images/tools/icons/event_icons/ico-edit.png" border="0"></a></td>
                        	<?php endif;?>
                            <td><input type="checkbox" class='checkbox1' value="<?php echo $v->getId()?>"></td>
                    	</tr>
                         
		    <?php endforeach;?>
