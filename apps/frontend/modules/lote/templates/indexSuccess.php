<div class="main-col-w">
    <div class="main-col">
        <h1>Lotes Elaborados (Estado de Maduración Actual)</h1>
        <button class='accionar' style="padding: 5px; float: right; margin-bottom: 15px;">Accion</button>
        <br><br>
        <div align="right">
            <form name="form" method="post" action="lote">
                <table id="calendario" class="one-table">
                    <td>Desde</td><td><input type="text" name="filtrar_desde" id="filtrar_desde" readonly="true"/></td>
                    <td>Hasta</td><td><input type="text" name="filtrar_hasta" id="filtrar_hasta" readonly="true"/></td>
                </table>
            </form>
        </div>

        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                    <tr>
                        <th>N° Lote</th>
                        <th>Fecha Elaboración</th>
                        <th>N° Elaboración</th>
                        <th width="20%">Producto</th>
                        <th>Comentarios</th>
                        <th>Cantidad Inicial</th>
                        <th>Cantidad Actual</th>
                        <th>Rendimiento</th>
                        <th>Estado</th>
                        <th>Deshacer</th>
                        <th>Ver</th>
                        <?php if($sf_user->hasPermission("Ver_Produccion_ProduccionReal_EditarLotesElaborados")):?>
                            <th>Modificar</th>
                        <?php endif;?>
                        <th>Acción</th>
                    </tr>
            </thead>
            <tbody>
                    <tr>
                            <td colspan="3" class="dataTables_empty">Cargando datos del servidor</td>
                    </tr>
            </tbody>
            <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <?php if($sf_user->hasPermission("Ver_Produccion_ProduccionReal_EditarLotesElaborados")):?>
                            <th></th>
                        <?php endif;?>
                        <th></th>
                    </tr>
            </tfoot>
        </table>
    </div>
</div>
<form action="lote/grupo" method="POST" class="form_grupo" style="display:none"></form>
<div class="side-col last">

<div class="msidebar rounded">
  <div style="padding: 3px 4px 15px 14px">
    <div id="filter_settings">
      <h2>Herramientas</h2>
      <div id="filter_settings_body">
            <form name="form" method="post" action="lote/filter">
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

                    <!-- <option value="En Maduración">En Maduración</option> -->
                    
                    
                    
                </select></p>
            </form>
      </div>
    </div>
  </div>

  <div class="cut">&nbsp;</div>
</div>
</div>



<script type="text/javascript">
/* Custom filtering function which will filter data in column four between two values */
$.fn.dataTableExt.afnFiltering.push(
	function( oSettings, aData, iDataIndex ) {
		// "date-range" is the id for my input
 		var dateMin = $('#filtrar_desde').attr("value");
                var dateMax = $('#filtrar_hasta').attr("value");

		// parse the range from a single field into min and max, remove " - "
		dateMin = dateMin.substring(6,10) + dateMin.substring(3,5) + dateMin.substring(0,2);
		dateMax = dateMax.substring(6,10) + dateMax.substring(3,5) + dateMax.substring(0,2);

		// 4 here is the column where my dates are.
 		var date = aData[1];

 		// remove the "-" characters
 		// 2010-04-11 -> 20100411
 		date = date.substring(6,10) + date.substring(3,5) + date.substring(0,2);

		// run through cases
 		if ( dateMin == "" && date <= dateMax){
 			return true;
 		}
 		else if ( dateMin =="" && date <= dateMax ){
 			return true;
 		}
 		else if ( dateMin <= date && "" == dateMax ){
 			return true;
 		}
 		else if ( dateMin <= date && date <= dateMax ){
 			return true;
 		}
		// all failed
 		return false;
	}
);

$(document).ready(function() {
	/* Initialise datatables */
    var oTable = $('#example').dataTable( {
		"oLanguage": {
			"sSearch": "Buscar en todas las columnas:"
		},
        "bStateSave": true,
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo url_for('lote/get_data') ?>",
        "aoColumnDefs": [
            { "aTargets": [ 1 ], "sType": "uk_date" }
        ],
        "fnServerParams": function ( aoData ) {
          aoData.push( { "iSortCol_0": 1, "iSortingCols": 1} );
        },
        "fnInitComplete": function() {
			/* Add a select menu for each TH element in the table footer */
            $("tfoot th").each( function ( i ) {
                if(i != 0 && i != 4 && i != 9 && i!= 10 && i != 11){
                    this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(i) );
                    $('select', this).change( function () {
                    oTable.fnFilter( $(this).val(), i );
                    } );
                }
            } );
		},
        "fnDrawCallback": function() {
            $('a.jt').cluetip({
                cluetipClass: 'jtip',
                arrows: true,
                dropShadow: false,
                hoverIntent: false,
                sticky: true,
                mouseOutClose: true,
                closePosition: 'title',
                closeText: '<img src="..../images/cross.png" alt="close" />'
            });
        }   
	} );

	/* Remove all filtering */
	oTable.fnFilterClear();

	/* Add event listeners to the two range filtering inputs */
	$('#filtrar_desde').change( function() { oTable.fnDraw(); } );
	$('#filtrar_hasta').change( function() { oTable.fnDraw(); } );
} );
</script>

<script type="text/javascript">
var oCache = {
    iCacheLower: -1
};
 
function fnSetKey( aoData, sKey, mValue )
{
    for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )
    {
        if ( aoData[i].name == sKey )
        {
            aoData[i].value = mValue;
        }
    }
}
 
function fnGetKey( aoData, sKey )
{
    for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )
    {
        if ( aoData[i].name == sKey )
        {
            return aoData[i].value;
        }
    }
    return null;
}
 
function fnDataTablesPipeline ( sSource, aoData, fnCallback ) {
    var iPipe = 5; /* Ajust the pipe size */
     
    var bNeedServer = false;
    var sEcho = fnGetKey(aoData, "sEcho");
    var iRequestStart = fnGetKey(aoData, "iDisplayStart");
    var iRequestLength = fnGetKey(aoData, "iDisplayLength");
    var iRequestEnd = iRequestStart + iRequestLength;
    oCache.iDisplayStart = iRequestStart;
     
    /* outside pipeline? */
    if ( oCache.iCacheLower < 0 || iRequestStart < oCache.iCacheLower || iRequestEnd > oCache.iCacheUpper )
    {
        bNeedServer = true;
    }
     
    /* sorting etc changed? */
    if ( oCache.lastRequest && !bNeedServer )
    {
        for( var i=0, iLen=aoData.length ; i<iLen ; i++ )
        {
            if ( aoData[i].name != "iDisplayStart" && aoData[i].name != "iDisplayLength" && aoData[i].name != "sEcho" )
            {
                if ( aoData[i].value != oCache.lastRequest[i].value )
                {
                    bNeedServer = true;
                    break;
                }
            }
        }
    }
     
    /* Store the request for checking next time around */
    oCache.lastRequest = aoData.slice();
     
    if ( bNeedServer )
    {
        if ( iRequestStart < oCache.iCacheLower )
        {
            iRequestStart = iRequestStart - (iRequestLength*(iPipe-1));
            if ( iRequestStart < 0 )
            {
                iRequestStart = 0;
            }
        }
        oCache.iCacheLower = iRequestStart;
        oCache.iCacheUpper = iRequestStart + (iRequestLength * iPipe);
        oCache.iDisplayLength = fnGetKey( aoData, "iDisplayLength" );
        fnSetKey( aoData, "iDisplayStart", iRequestStart );
        fnSetKey( aoData, "iDisplayLength", iRequestLength*iPipe );
         
        $.getJSON( sSource, aoData, function (json) { 
            /* Callback processing */
            oCache.lastJson = jQuery.extend(true, {}, json);
             
            if ( oCache.iCacheLower != oCache.iDisplayStart )
            {
                json.aaData.splice( 0, oCache.iDisplayStart-oCache.iCacheLower );
            }
            json.aaData.splice( oCache.iDisplayLength, json.aaData.length );
             
            fnCallback(json)
        } );
    }
    else
    {
        json = jQuery.extend(true, {}, oCache.lastJson);
        json.sEcho = sEcho; /* Update the echo for each response */
        json.aaData.splice( 0, iRequestStart-oCache.iCacheLower );
        json.aaData.splice( iRequestLength, json.aaData.length );
        fnCallback(json);
        return;
    }
	
	
}


	

// } );
</script>
</script>
<script type="text/javascript">
    // para hacer la siguiente accion de varias ordenes de venta al mismo tiempo
    $(document).ready(function(){  
  
    $(".accionar").click(function() {  
        var primero = "algo";
        var bool =false;
        var haycheckeados=false;
        var arregloCheckbox = [];
        $(".checkbox1").each(function(){
            if($(this).is(':checked')) {
                haycheckeados=true;
                if(primero == "algo") {
                    primero = $(this).attr('accion');
                }
                var estado = $(this).attr('accion');
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
  
}); 
</script> 
