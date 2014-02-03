/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var subscribeValidators = function()
{
    $('#lote_cantidad_recibida').change(function()
    {
        var cantidadRecibida = parseInt($('#lote_cantidad_recibida').val());
        var cantidadActual = parseInt($('#lote_cantidad_actual').val());
        if(cantidadRecibida < cantidadActual)
        {
            $('#error_label_cantidad_recibida').html('<b>Atención:</b> La cantidad recibida en Santiago es <b>MENOR</b> a la cantidad enviada desde Valdivia.');
        }
        else if(cantidadRecibida > cantidadActual)
        {
             $('#error_label_cantidad_recibida').html('<b>Atención:</b> La cantidad recibida en Santiago es <b>MAYOR</b> a la cantidad enviada desde Valdivia.');
        }
        else
        {
            $('#error_label_cantidad_recibida').html('');
        }
    });
	$('#lote_cantidad_danada_stgo').change(function()
    {
        var cantidadRecibida = parseInt($('#lote_cantidad_danada_stgo').val());
        var cantidadActual = parseInt($('#lote_cantidad_actual').val());
        if(cantidadRecibida > cantidadActual)
        {
             $('#error_label_cantidad_danada_stgo').html('<b>Atención:</b> La cantidad da&ntilde;ada en Santiago es <b>MAYOR</b> a la cantidad enviada desde Valdivia.');
        }
        else
        {
            $('#error_label_cantidad_danada_stgo').html('');
        }
    });
$('#lote_cantidad_ff_stgo').change(function()
    {
        var cantidadRecibida = parseInt($('#lote_cantidad_ff_stgo').val());
        var cantidadActual = parseInt($('#lote_cantidad_actual').val());
        var cantidadFf = parseInt($('#lote_cantidad_danada_stgo').val());
        if(cantidadRecibida > cantidadActual-cantidadFf)
        {
             $('#error_label_cantidad_ff_stgo').html('<b>Atención:</b>La cantidad fuera de formato en Santiago es <b>MAYOR</b>a la cantidad enviada desde Valdivia.');
        }
        else
        {
            $('#error_label_cantidad_ff_stgo').html('');
        }
    });
}

$(document).ready(subscribeValidators);
