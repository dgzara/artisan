/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//Verifica que sea un número el parámtro recibido...
function isNumber(n) {
          return !isNaN(parseFloat(n)) && isFinite(n);
}

//Assert if is number...

function assertNumber(n)
       {   
           if(!isNumber(n))
           {
               alert("Se debe ingresar un número válido.");
               return false;
           }
           return true;
       }

//Recibe como parámetro un string y te entrega un arreglo con todos los números incluidos en él...
function extractNums(str){
            return str.match(/\d+/g);
}
var correct = true;

function validateForm()
{
    if(!correct)
    {
        alert("El formulario no es valido. Revisa todos los campos.");
    }

    return correct;
}

function runValidationsOrdenCompra()
{
    //esto es para almacenar el valor que viene por default...
            $('label:regex(id,orden_compra_insumo_[0-9]*_neto_default)').each(function(index, value) {
                    //almacenamos el default...
                    var id = extractNums(value.id)[0];
                   // $(value).html($('#orden_compra_insumo_' + id + '_neto').val());
                });

             $('input:regex(id,orden_compra_insumo_[0-9]*_cantidad)').change(function(sender) {
                 var id = extractNums(sender.currentTarget.id)[0];
		if(assertNumber($(sender.currentTarget).val()))
		{
                 if($(sender.currentTarget).val() >  0)
                 {
                    if($('#orden_compra_insumo_' + id + '_neto').val() <= 0)
                    {
                           $('#orden_compra_insumo_' + id + '_neto_default').html("<br>El precio debe ser <b>MAYOR</b> a cero.");
                           correct = false;
                    }
                    else
                    {
                        $('#orden_compra_insumo_' + id + '_neto_default').html("");
                        correct = true;
                    }
                 }
                 else if($(sender.currentTarget).val() < 0)
                 {
                     $('#orden_compra_insumo_' + id + '_neto_default').html("<br>La cantidad debe ser <b>MAYOR</b> a cero.");
                     correct = false;
                }
                 else
                 {
                     $('#orden_compra_insumo_' + id + '_neto_default').html("");
                     correct = true;
                 }
                
		}
		else 
		{
			correct = false;
		}});


                $('input:regex(id,orden_compra_insumo_[0-9]*_neto)').change(function(sender){
                    var id = extractNums(sender.currentTarget.id)[0];
                    if($(sender.currentTarget).val() <=  0 || $(sender.currentTarget).val() == "")
                     {
                        if($('#orden_compra_insumo_' + id + '_cantidad').val() > 0)
                        {
                               $('#orden_compra_insumo_' + id + '_neto_default').html("<br>El precio debe ser <b>MAYOR</b> a cero.");
                               correct = false;
                        }
                        else
                        {
                            $('#orden_compra_insumo_' + id + '_neto_default').html("<br>La cantidad debe ser <b>MAYOR</b> a cero.");
                            correct = false;
                        }
                     }
                     else
                     {
                            $('#orden_compra_insumo_' + id + '_neto_default').html("");
                            correct = true;
                     }
                });
}

function runValidationsOrdenVenta()
{
    //esto es para almacenar el valor que viene por default...
    $('label:regex(id,orden_venta_producto_[0-9]*_neto_default)').each(function(index, value) {
        //almacenamos el default...
        var id = extractNums(value.id)[0];
       // $(value).html($('#orden_compra_insumo_' + id + '_neto').val());
    });

    $('input:regex(id,orden_venta_producto_[0-9]*_cantidad)').change(function(sender) {
        var id = extractNums(sender.currentTarget.id)[0];
        if($(sender.currentTarget).val() >  0)
        {
            if($('#orden_venta_producto_' + id + '_neto').val() <= 0)
            {
                   $('#orden_venta_producto_' + id + '_neto_default').html("<br>El precio debe ser <b>MAYOR</b> a cero.");
                   correct = false;
            }
            else
            {
                $('#orden_venta_producto_' + id + '_neto_default').html("");
                correct = true;
            }
        }
        else if($(sender.currentTarget).val() < 0)
        {
             $('#orden_venta_producto_' + id + '_neto_default').html("<br>La cantidad debe ser <b>MAYOR</b> a cero.");
             correct = false;
        }
        else
        {
             $('#orden_venta_producto_' + id + '_neto_default').html("");
             correct = true;
        }
    });


    $('input:regex(id,orden_venta_producto_[0-9]*_neto)').change(function(sender){
        var id = extractNums(sender.currentTarget.id)[0];
        if($(sender.currentTarget).val() <=  0 || $(sender.currentTarget).val() == "")
         {
            if($('#orden_venta_producto_' + id + '_cantidad').val() > 0)
            {
                   $('#orden_venta_producto_' + id + '_neto_default').html("<br>El precio debe ser <b>MAYOR</b> a cero.");
                   correct = false;
            }
            else
            {
                $('#orden_venta_producto_' + id + '_neto_default').html("<br>La cantidad debe ser <b>MAYOR</b> a cero.");
                correct = false;
            }
         }
         else
         {
                $('#orden_venta_producto_' + id + '_neto_default').html("");
                correct = true;
         }
    });

    $('input:regex(id,orden_venta_producto_[0-9]*_descuento)').change(function(sender){
        var id = extractNums(sender.currentTarget.id)[0];
        if($(sender.currentTarget).val() < 0 || $(sender.currentTarget).val()> 100)
        {
            $('#orden_venta_producto_' + id + '_neto_default').html("<br>El descuento debe estar entre 0 y 100.");
            correct = false;
        }
           

         else
         {
            $('#orden_venta_producto_' + id + '_neto_default').html("");
            correct = true;
         }
    });

}

function runValidationsLote(sufix)
{
    $('input:regex(id,lote_[0-9]*_cantidad'+sufix+')').change(function(sender) {
        if(assertNumber($(sender.currentTarget).val()))
        {
            if($(sender.currentTarget).val() < 0)
            {
                $('#lote_'+extractNums(sender.currentTarget.id)+'_cantidad_error_label').html("La cantidad debe ser <strong>MAYOR o IGUAL</strong> a cero.");
                correct = false;
            }
            else
            {
                var correctTemp = true;
                $('input:regex(id,lote_[0-9]*_cantidad'+sufix+')').each(function(sender){
                    if($(sender.currentTarget).val() < 0)
                        correctTemp = false;
                });
                correct = correctTemp;
                $('#lote_'+extractNums(sender.currentTarget.id)+'_cantidad_error_label').html("");
            }
        }
        else
        {
         $(sender.currentTarget).html("");
         correct = false;
        }
    });
}

function cargarProductosPorProveedor(){
    $('#productos').hide();
    $('#loader').show();
    $('#productos').load(
        $('#form_insumos_proveedor').attr('action'),
        {
            proveedor_id: $('#orden_compra_proveedor_id').val()
        },
        function() {
            $('#loader').hide();
            $('#productos').show();

            runValidationsOrdenCompra();
        }
        );
}

function cargarProductosPorCliente(){
    $('#productos').hide();
    $('#loader').show();
    $('#productos').load(
        $('#form_productos_cliente').attr('action'),
        {
            cliente_id: $('#orden_venta_cliente_id').val()
        },
        function() {
            $('#loader').hide();
            $('#productos').show();

            runValidationsOrdenVenta();
        }
        );
    
}


function cargarLocalesPorCliente(){
    $('#locales').hide();
    $('#loader').show();
    $('#locales').load(
        $('#form_locales_cliente').attr('action'),
        {
            cliente_id: $('#orden_venta_cliente_id').val()
        },
        function() {
            $('#loader').hide();
            $('#locales').show();
        }
        );
}

function cargarProveedoresPorInsumo(){
    $('#proveedores').hide();
    $('#loader').show();
    $('#proveedores').load(
        $('#form_insumos_proveedor').attr('action'),
        {
            insumo_id: $('#proveedor_insumo_insumo_id').val()
        },
        function() {
            $('#loader').hide();
            $('#proveedores').show();
        }
        );
}

function runValidationsInsumos()
{
     $('input:regex(id,ingrediente_[0-9]*_cantidad_usada)').change(function(sender) {
        if(assertNumber($(sender.currentTarget).val()))
        {
            var cantReq =  parseInt($('#cantidad_requerida_'+extractNums(sender.currentTarget.id)).html());
            if(parseInt($(sender.currentTarget).val()) > cantReq)
            {
               alert("No hay suficientes insumos.");
               $(sender.currentTarget).val("");
            }
            else if(parseInt($(sender.currentTarget).val()) < 0)
            {
                alert("El insumo es menor que cero.");
               $(sender.currentTarget).val("");
            }
        }
        else
        {
            $(sender.currentTarget).html("");
        }
     });
 }

function runValidationsInsumosFinal()
{
    var valid = true;
    $('input:regex(id,ingrediente_[0-9]*_cantidad_usada)').each(function(idx,element) {
        if($(element).val()== "")
        {
            valid = false;
        }        
     });

     if(!valid)
         alert("No se cargaron todos los ingredientes utilizados.");

     return valid;
}

function cargarPautaInstrucciones(){
    $('#instrucciones').hide();
    $('#loader').show();
    $('#instrucciones').load(
        $('#form_instrucciones').attr('action'),
        {
            plantilla_pauta_id: $('#pauta_plantilla_pauta_id').val()
        },
        function() {
            $('#loader').hide();
            $('#instrucciones').show();

            runValidationsInsumos();
            runValidationsLote("\\b");
        }
    );
}

function agregarEtapa(tabla){
    var l = $('#'+tabla+' tr.etapa').length;
    $('#'+tabla+' > tbody:last').append('<tr class="etapa" id="etapa_'+l+'"><td colspan="3">'+l+'</td></tr>');

    $('#etapa_'+l).load(
        $('#form_etapas').attr('action'),
        {
            i: l
        },
        function() {
            $('#etapa_'+l).show();
        }
    );
}

function agregarIngrediente(tabla){
    var l = $('#'+tabla+' tr.ingrediente').length;
    $('#'+tabla+' > tbody:last').append('<tr class="ingrediente" id="ingrediente_'+l+'"><td colspan="3">'+l+'</td></tr>');

    $('#ingrediente_'+l).load(
        $('#form_ingredientes').attr('action'),
        {
            i: l
        },
        function() {
            $('#ingrediente_'+l).show();
        }
    );
}

function agregarInstruccion(i, j, tabla){
    var l = $('#'+tabla+' tr.instruccion').length;
    $('#'+tabla+' > tbody:last').append('<tr class="instruccion" id="layer_'+i+'_'+l+'"><td colspan="3">'+l+'</div></td></tr>');

    $('#layer_'+i+'_'+l).load(
        $('#form_instrucciones').attr('action'),
        {
            i: i,
            j: l
        },
        function() {
            $('#layer_'+i+'_'+l).show();
        }
    );
    
}

function agregarDescriptor(tabla){
    var l = $('#'+tabla+' tr.descriptor').length;
    $('#'+tabla+' > tbody:last').append('<tr class="descriptor" id="descriptor_'+l+'"><td colspan="5">'+(l+1)+'</td></tr>');

    $('#descriptor_'+l).load(
        $('#form_descriptor').attr('action'),
        {
            i: l
        },
        function() {
            $('#descriptor_'+l).show();
        }
    );
}

function borrarFila(fila){
    $('#'+fila).remove();
}

function cambiarAreaDeCosto(area_de_costos_id, centro_de_costos_id){
    var id = area_de_costos_id;
    var id2 = centro_de_costos_id;
    
    $('#costos_indirectos_area_de_costos_id').load(
        $('#cambioArea').attr('action'),
        {
            area_de_costos_id: id
        },
        function() {
            
        }
    );
        
    $('#costos_indirectos_centro_de_costos_id').load(
        $('#cambioCentro').attr('action'),
        {
            area_de_costos_id: id,
            centro_de_costos_id: id2
        },
        function() {
        }
    );
}

function cambiarAreaDeCosto1(area_de_costos_id, centro_de_costos_id, iterador){
    var id = area_de_costos_id;
    var id2 = centro_de_costos_id;
    $('#costo_'+iterador+'_area_de_costos_id').load(
        $('#cambioArea').attr('action'),
        {
            area_de_costos_id: id
        },
        function() {
            
        }
    );
        
    $('#costo_'+iterador+'_centro_de_costos_id').load(
        $('#cambioCentro').attr('action'),
        {
            area_de_costos_id: id,
            centro_de_costos_id: id2
        },
        function() {
        }
    );
}

function cambiarCentroDeCosto(centro_de_costos_id){
    var id = centro_de_costos_id;
    $('#costos_indirectos_centro_de_costos_id').load(
        $('#cambioCentro').attr('action'),
        {
            area_de_costos_id: $('#costos_indirectos_area_de_costos_id').val(),
            centro_de_costos_id: id
        },
        function() {
        }
    );
}

function cambiarCentroDeCosto1(centro_de_costos_id, iterador){
    var id = centro_de_costos_id;
    $('#costo_'+iterador+'_centro_de_costos_id').load(
        $('#cambioCentro').attr('action'),
        {
            area_de_costos_id: $('#costo_'+iterador+'_area_de_costos_id').val(),
            centro_de_costos_id: id
        },
        function() {
        }
    );
}

function cambiarMonto(){
    $('#costos_indirectos_monto').load(
        $('#cambioMonto').attr('action'),
    {
            centro_de_costos_id: $('#costos_indirectos_centro_de_costos_id').val()
        },
    function(a,b,c) {

            var valor = eval("(" + a + ")");
            if(!$('#costos_indirectos_monto').val()){
        $('#costos_indirectos_monto').val( valor.monto);
            }
        $('#costos_indirectos_descripcion').val(valor.desc);

        }
    );
        
}

function cambiarMonto1(iterador){
    $('#costo_'+iterador+'_monto').load(
        $('#cambioMonto').attr('action'),
    {
            centro_de_costos_id: $('#costo_'+iterador+'_centro_de_costos_id').val()
        },
    function(a,b,c) {

            var valor = eval("(" + a + ")");
            if(!$('#costo_'+iterador+'_monto').val()){
        $('#costo_'+iterador+'_monto').val( valor.monto);
            }
        $('#costo_'+iterador+'_descripcion').val(valor.desc);

        }
    );
        
}

function cambiarCliente(){
    $('#orden_venta_cliente_id').load(
        $('#cambioCliente').attr('action'),
        {

        },
        function() {
        }
    );

    $('#orden_venta_local_id').load(
        $('#cambioLocal').attr('action'),
        {
            cliente_id: 0
        },
        function() {
        }
    );
}

function cambiarLocal(){
    $('#orden_venta_local_id').load(
        $('#cambioLocal').attr('action'),
        {
            cliente_id: $('#orden_venta_cliente_id').val()
        },
        function() {
        }
    );

    this.cargarProductosPorCliente();
}

// Trazabilidad
function display_ramas(id)
{
    if ($('#productos' + id).css('display') == 'none')
    {
        $('#productos' + id).slideDown('slow');
    }
    else{
        {$('#productos' + id).slideUp('slow');}
    }
}

function display_productos(id)
{
    if ($('#cuadrado' + id).css('display') == 'none')
    {
        $('#cuadrado' + id).slideDown('slow');
        $('#ver'+id).hide();
        $('#loader'+id).show();
        $('#ver'+id).load(
            $('#form_trazabilidad').attr('action'),
            {
                producto_id: id
            },
            function() {
                $('#loader'+id).hide();
                $('#ver'+id).show();
            }
        );
    }
    else{
        {$('#cuadrado' + id).slideUp('slow');}

    }
}