<?php

	ini_set('memory_limit', '256M');

?>
<div class="main-col-w">
    <div class="main-col">
        <div align="right">
            <table id="calendario" class="one-table">
                <td>Desde</td><td><input type="text" name="filtrar_desde" id="filtrar_desde" readonly="true"/></td>
                <td>Hasta</td><td><input type="text" name="filtrar_hasta" id="filtrar_hasta" readonly="true"/></td>
            </table>
        </div>
        <h1>Lista Orden de Venta</h1>
        <button class='accionar' style="padding: 5px; float: right; margin-bottom: 15px;">Accion</button>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                    <tr>
                        <th>N°</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>N° Orden Compra</th>
			            <th>Local</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Deshacer</th>
                        <th>Ver</th>
                        <?php if($sf_user->hasPermission("Ver_Ventas_OrdenVenta_Editar")):?>
                            <th>Modificar</th>
                        <?php endif;?>
                        <th>Acción</th>
                    </tr>
            </thead>
            <tbody>
                
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
                        	<td><?php echo $v->getValorTotal()?></td>
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
            <tr><td colspan="11" style="text-align: center;"><button style="padding: 20px; padding-left: 100px; padding-right: 100px;" id="load_more">Cargar mas registros</button></td></tr>
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
            <form name="form" method="post" action="ordenventa/filter">
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

  <form action="ordenventa/grupo" method="POST" class="form_grupo" style="display:none"></form>

  <div class="cut">&nbsp;</div>
</div>
</div>
<script type="text/javascript">
    var page_actual = 1;
    // para hacer la siguiente accion de varias ordenes de venta al mismo tiempo
    $(document).ready(function(){  
  
    $(".accionar").click(function() {  
        var primero = "algo";
        var haycheckeados=false;
        var bool =false;
        var arregloCheckbox = [];
        $(".checkbox1").each(function(){
            if($(this).is(':checked')) {
                haycheckeados=true;
                if(primero == "algo") {
                    primero = $(this).parent().parent().find('.estado a').html();
                }
                var estado = $(this).parent().parent().find('.estado a').html();
                
                if(estado == primero){
                    arregloCheckbox.push($(this).attr('value'));
                }
                else{
                    bool=true;
                }

            }
        });
        if(bool){
            alert("Ha chequeado ordenes que corresponden a más de un tipo de acción. Debe elegir acciones que correspondan al mismo tipo.");
        }
        else
        {
                    var i;
        for(i=0; i < arregloCheckbox.length; i++) {
            $('.form_grupo').append("<input type='hidden' name='grupo[]' value='"+ arregloCheckbox[i] +"' />");
        }
        if(haycheckeados)
        {
            $('.form_grupo').submit();
        }
        else{ alert("Debe seleccionar alguna orden para accionar.");}
        }
        
    });

    load_qtip();

    $("#load_more").click(function() {
        $(this).fadeOut('fast', function() { $(this).hide(); });
        $.ajax({
          type: 'POST',
          data: {page: page_actual},
          url: "<?php echo url_for('ordenventa/index') ?>",
          success: function(data) {
            page_actual++;
            $('.display').find('tr').last().before(data);
            load_qtip();
            $('#load_more').show();
          },
          error: function() {
            alert('Error! No se recibió información!');
          }
        });
    });

  
});

    function load_qtip()
    {
        $('a.jt').cluetip({
            cluetipClass: 'jtip',
            width: 600,
            arrows: true,
            dropShadow: false,
            hoverIntent: false,
            sticky: true,
            mouseOutClose: true,
            closePosition: 'title',
            closeText: '<img src="../web/images/cross.png" alt="close" />'
        });
    }
</script>
