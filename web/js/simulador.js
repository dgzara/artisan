
       //Carga los paquetes necesarios para los gráficos de Google Chart API...
       google.load('visualization', '1', {packages: ['corechart','table']});

       function calcularSumaDentroAn(idNameDestiny, nameFrom, idx)
       {
          var toSum = $('input:regex(id,'+ nameFrom +'An'+idx+'Prd[0-9]*)');
          var sum = 0;
          for(i=0; i< toSum.length; i++)
          {
              sum += parseFloat(toSum[i].value);
          }
                    
          return sum.toFixed(3);
       }

       function calcularSumaAn(idNameDestiny)
       {          
          var toSum = $('input:regex(id,alphaAn[0-9]*\\b)');
          var sum = 0;
          for(i=0; i< toSum.length; i++)
          {
              sum += parseFloat(toSum[i].value);
          }          
          return sum.toFixed(3);
       }

       function getCostoArea(idx)
       {
          return parseFloat($('#CITotal').html())*parseFloat($('#alphaAn'+idx).val());
       }

       function setCIProductosArea(ciArea, idxArea, idxPrd)
       {
           var alpha = parseFloat($('#alphaAn'+idxArea+'Prd'+idxPrd).val());
           var prod = parseFloat($('#produccionAn'+idxArea+'Prd'+idxPrd).val());
           var value;
           if(prod > 0)
                value =alpha*ciArea/prod;
           else
               value = 0;
           dataTables[idxArea].setCell(idxPrd,2, parseFloat(value.toFixed(3)));
           return value;
       }

        //divTableId = table_0 tabla de datos del area de negocios 1 ...
        //content contenido de la tabla...
       function generateTable(idx, msg)
       {
          var table = '<form id=form'+idx+'><table><thead><tr><th>Producto</th><th>Producción</th><th>beta</th></thead><tbody>';
          var j = 0;
          for (var post in msg)
          {
            var row = '<tr>';

            row += '<td>'+  msg[post].nombre + '</td>';
            row += '<td> <input id =produccionAn'+idx+'Prd'+j+' type="text" name="produccion" /><label id="produccionHiddenAn'+ idx + 'Prd' + j+'" style="display:none"></td>';
            row += '<td> <input id =alphaAn'+idx+'Prd'+j+' type="range"  min="0" max="1" step="0.0000000000001" name="alpha" />' /*+ msg[post].alpha*/ + '</td>';
            row += '</tr>';
            table += row;
            j++;
          }
          table += '<tr><td><h2>TOTAL: </h2></td><td><h2><label id=totalProduccion'+idx+'></label> <label id=baseProduccion'+idx+'></label><label style="display:none;" id=baseProduccionHidden'+idx+'></label></h2></td><td><h2><label id=totalAlpha'+idx+'></label></h2></td></tr>';
          table += '</tbody></table></form>';

          $('#table_'+ idx).html(table);
       }

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

       function changeProductionHandler(ev)
       {
           var value = parseFloat(ev.currentTarget.value);
           if(assertNumber(value))//Revisamos si lo ingresado corresponde a un número...
           {
                   var cellPos = extractNums(ev.currentTarget.id);
                   cellPos[0] = parseInt(cellPos[0]);//Area de Negocio i
                   cellPos[1] = parseInt(cellPos[1]);//Producto j
           //Costo Indirecto unitario...

           var ciu = setCIProductosArea(getCostoArea(cellPos[0]), cellPos[0], cellPos[1]);
           drawGraphById(cellPos[0]);

           var diff = calcularSumaDentroAn("totalProduccion"+ cellPos[0], "produccion", cellPos[0]) - $('#baseProduccionHidden'+ cellPos[0]).html();
           $('#baseProduccion'+ cellPos[0]).html("( " + diff+ " )");

           $('#totalProduccion'+ cellPos[0]).html(Math.floor(calcularSumaDentroAn("totalProduccion"+ cellPos[0], "produccion", cellPos[0])));

           //Cambio del valor CD extra...
           var lastValuePrd = parseFloat($('#produccionHiddenAn'+ cellPos[0]+ 'Prd'+ cellPos[1]).html());
           var change = value - lastValuePrd;
           var cd = dataTables[cellPos[0]].getValue(cellPos[1], 1);
           var cdExtra = parseFloat($('#CDExtra').html());
           cdExtra += change*cd;  
           $('#CDExtra').html(cdExtra);
           $('#produccionHiddenAn'+ cellPos[0]+ 'Prd'+ cellPos[1]).html(value);

           //Actualizacion de Costo Directo Total...
           $('#CDTotal').html(cdExtra + parseFloat($('#CDTotal').html()));

           //Actualización de Costo Total...
           $('#CTotal').html(calculoCostoTotal());

           }
       }

       function findEqualZero(arr)
       {
           var num = 0;
           for(var i=0; i< arr.length; i++)
           {
               if(parseFloat($(arr[i]).val()) == 0)
               {
                   num++;
               }
           }
           return num;
       }

       function checkInnerOverFlow(idAn,idPrd, sumaAn, overflow )
       {
           var maxDivs = $('input:regex(id,alphaAn'+idAn+'Prd[0-9]*)').length;
            var inZero = findEqualZero($('input:regex(id,alphaAn'+idAn+'Prd[0-9]*)'));
            var divPossible = maxDivs - inZero;

            var deltaAlphaUnitario = (1 - sumaAn)/divPossible;

            $('input:regex(id,alphaAn'+idAn+'Prd[0-9]*)').each(function(args)
            {
                if(extractNums(this.id)[1] != idPrd)
                {
                    if(deltaAlphaUnitario < 0)
                    {
                        if( parseFloat($(this).val()) + deltaAlphaUnitario < 0)
                        {
                            overflow -= parseFloat($(this).val());
                            $(this).val(0);
                            if(divPossible != 1)
                            {
                                divPossible--;
                            }
                            console.log("divPossible : " +divPossible);
                        }
                    }
                    else
                    {
                        if( parseFloat($(this).val()) + deltaAlphaUnitario > 1)
                        {
                            overflow -= 1 - parseFloat($(this).val());
                            $(this).val(1);
                            if(divPossible != 1)
                            {
                                divPossible--;///
                            }
                            console.log("divPossible : " +divPossible);
                        }
                    }
                }
            });

             console.log("divPossible : " +divPossible);

             var deltaAlphaUnitario2;
             if(deltaAlphaUnitario < 0)
             {
                 deltaAlphaUnitario2 = -overflow/divPossible;
             }
             else
             {
                 deltaAlphaUnitario2 = overflow/divPossible;
             }
             console.log("deltaAlphaUnitario2 : " +deltaAlphaUnitario2);

            $('input:regex(id,alphaAn'+idAn+'Prd[0-9]*)').each(function(args)
            {
                if(extractNums(this.id)[1] != idPrd)
                {
                    if(deltaAlphaUnitario2 < 0)
                    {
                        if( parseFloat($(this).val()) + deltaAlphaUnitario >= 0 && (parseFloat($(this).val()) + deltaAlphaUnitario <= 1))
                        {
                            overflow += deltaAlphaUnitario2;
                            $(this).val(parseFloat($(this).val()) + deltaAlphaUnitario2);
                        }
                    }
                    else
                    {
                        if( parseFloat($(this).val()) + deltaAlphaUnitario <= 1)
                        {
                            overflow -= deltaAlphaUnitario2;
                            $(this).val(parseFloat($(this).val()) + deltaAlphaUnitario2);
                        }
                    }
                }
            });

            return overflow;
       }

       function changeSliderHandler (ev)
       {
            var cellPos = extractNums(ev.currentTarget.id);
            cellPos[0] = parseInt(cellPos[0]);//Area de Negocio i
            cellPos[1] = parseInt(cellPos[1]);//Producto j

            var sumaAn = calcularSumaDentroAn("totalAlpha"+ cellPos[0], "alpha", cellPos[0]);            

            var overflow = Math.abs(1 - sumaAn);
            overflow = checkInnerOverFlow(cellPos[0], cellPos[1], sumaAn, overflow );
            console.log("overflow : " +overflow);
            
            sumaAn = calcularSumaDentroAn("totalAlpha"+ cellPos[0], "alpha", cellPos[0]);
            $('#totalAlpha'+ cellPos[0]).html(sumaAn);
            assertOneDiff('totalAlpha'+cellPos[0], sumaAn);

            var ciArea = getCostoArea(cellPos[0]);
            for(var j=0;j < $('input:regex(id,alphaAn'+cellPos[0]+'Prd[0-9]*)').length; j++)
            {
                setCIProductosArea(ciArea, cellPos[0], j);
            }

            //Render..
            drawGraphById(cellPos[0]);
       }

       function checkAnOverFlow(idAn, sumaAn, overflow )
       {
           var maxDivs = $('input:regex(id,alphaAn[0-9]*\\b)').length;
            var inZero = findEqualZero($('input:regex(id,alphaAn[0-9]*\\b)'));
            var divPossible = maxDivs - inZero;

            var deltaAlphaUnitario = (1 - sumaAn)/divPossible;

            $('input:regex(id,alphaAn[0-9]*\\b)').each(function(args)
            {
                if(extractNums(this.id)[0] != idAn)
                {
                    if(deltaAlphaUnitario < 0)
                    {
                        if( parseFloat($(this).val()) + deltaAlphaUnitario < 0)
                        {
                            overflow -= parseFloat($(this).val());
                            $(this).val(0);
                            if(divPossible != 1)
                            {
                                divPossible--;
                            }
                            console.log("divPossible : " +divPossible);
                        }
                    }
                    else
                    {
                        if( parseFloat($(this).val()) + deltaAlphaUnitario > 1)
                        {
                            overflow -= 1 - parseFloat($(this).val());
                            $(this).val(1);
                            if(divPossible != 1)
                            {
                                divPossible--;//
                            }
                            console.log("divPossible : " +divPossible);
                        }
                    }
                }
            });

             console.log("divPossible : " +divPossible);

             var deltaAlphaUnitario2;
             if(deltaAlphaUnitario < 0)
             {
                 deltaAlphaUnitario2 = -overflow/divPossible;

             }
             else
             {
                 deltaAlphaUnitario2 = overflow/divPossible;
             }
             console.log("deltaAlphaUnitario2 : " +deltaAlphaUnitario2);

            $('input:regex(id,alphaAn[0-9]*\\b)').each(function(args)
            {
                if(extractNums(this.id)[0] != idAn)
                {
                    if(deltaAlphaUnitario2 < 0)
                    {
                        if( parseFloat($(this).val()) + deltaAlphaUnitario >= 0 && (parseFloat($(this).val()) + deltaAlphaUnitario <= 1))
                        {
                            overflow += deltaAlphaUnitario2;
                            $(this).val(parseFloat($(this).val()) + deltaAlphaUnitario2);
                        }
                    }
                    else
                    {
                        if( parseFloat($(this).val()) + deltaAlphaUnitario <= 1)
                        {
                            overflow -= deltaAlphaUnitario2;
                            $(this).val(parseFloat($(this).val()) + deltaAlphaUnitario2);
                        }
                    }
                }
            });

            return overflow;
       }

       function assertOneDiff(id, sum)
       {
           $('#'+ id).html(sum);
            if(Math.abs(sum - 1) > 0.005)
            {
                $('#'+id).css('color', 'red');
            }
            else
            {
                $('#' +id).css('color', 'black');
            }
       }

       function changeSliderAnHandler(ev)
        {
            var value = parseFloat(ev.currentTarget.value);
            var cellPos = extractNums(ev.currentTarget.id);
            cellPos[0] = parseInt(cellPos[0]);
            cellPos[1] = parseInt(cellPos[1]);

            //Actulización de Costo asociado a la diferencia de Alpha con 1...
            var sum = calcularSumaAn("alphaAnTotal");
            var overflow = Math.abs(1 - sum);
            overflow = checkAnOverFlow(cellPos[0], sum, overflow );
            console.log("overflow : " +overflow);

            sum = calcularSumaAn("alphaAnTotal");
            $('#sumaAlpha').html(sum);
            
            assertOneDiff('sumaAlpha', sum);

            $('#sumaAlphaSlide').val(sum/2);

            //var alphaDif = sum - 1;
            //$('#valorDifAlpha').html(parseInt((alphaDif*parseFloat($('#CIFijo').html()))));
            //Actualización de Costo Total...
            //$('#CITotal').html(parseInt($('#valorDifAlpha').html()) + parseInt($('#CIFijo').html()));
           var lengthArea = $('input:regex(id,alphaAn[0-9]*\\b)').length;
           
           reCalculateCI();
            
        }

       function reCalculateCI()
       {
           var lengthArea = $('input:regex(id,alphaAn[0-9]*\\b)').length;

           for(var i=0; i < lengthArea; i++)
           {
               var lengthProd = $('input:regex(id,alphaAn'+i+'Prd[0-9]*)').length;
               for(var j=0; j < lengthProd; j++)
               {
                 setCIProductosArea(getCostoArea(i), i, j);
               }
               if($('#ver'+i).css("display") != 'none')
               {
                    drawGraphById(i);
               }
           }
       }

       function difExtraHandler()
       {
           var value = parseFloat($('#valorDifExtra').val());
           if(assertNumber(value))
           {
                 var sum = parseFloat($('#CIFijo').html()) + value;
                 $('#CITotal').html(sum);
           }
           reCalculateCI();
           $('#CTotal').html(calculoCostoTotal());
       }

       function actualizarCTUnitario(area, producto, valor)
       {
           dataTables[area].setCell(producto, 3, valor)
       }

       function calculoCostoTotal()
       {
           return parseFloat($('#CITotal').html()) + parseFloat($('#CDTotal').html());
       }

       var dataTables = []; //variable global (arreglo de tabla que contiene los datos del gráfico)
       var charts = [];     //variable global (arreglo que contiene los gráficos)
       var views  =[];
       
       
       function createGraphs() {

        var desde = $('#filtrar_desde').val();
	var desde1 = "'"+desde+"'";
        var hasta = $('#filtrar_hasta').val();
        var hasta1 = "'"+hasta+"'";
	//Aqui se carga el JSON con los datos del servidor..
        $.get("simulador/getDatos",{"from": desde, "to":hasta}, function(data)
         {
           var myJSONData = eval("(" + data + ")");
           //console.log(myJSONData);

       // myJSONData = myJSONData[0];
        // 1 : Cantidad de Areas de Negocio
        var pondAreaNegocio = 1 / myJSONData["areasNegocio"].length;
        var cdTotal = 0;
        // Create and draw the visualization.
        for(var i=0; i < myJSONData["areasNegocio"].length; i++)
        {
         //Dibujamos la tabla donde se listarán los productos y los atributos de produccion y alpha.
         generateTable(i, myJSONData["areasNegocio"][i]['productos']);

         //Creamos las columnas en la Tabla de Datos...
         var data = new google.visualization.DataTable();
         data.addColumn('string', 'Producto');
         data.addColumn('number', 'Costo Directo');
         data.addColumn('number', 'Costo Indirecto');
         
         $('#alphaAn'+i).val(pondAreaNegocio);
         $('#baseAn' +i).html(pondAreaNegocio);
         
         for(var j=0; j < myJSONData["areasNegocio"][i]['productos'].length; j++)
         {
             //ponderador de productos del área de negocio...

             var prd = myJSONData["areasNegocio"][i]["productos"][j]["Ni"];
             var pondProducto;
             var prdAn = myJSONData["areasNegocio"][i]["produccion"];
             if(prdAn > 0)
             {    
                pondProducto = prd/ prdAn;
             }
             else
             {
                 pondProducto = 0;
             }
             //Colocamos lo valores default en el form...
             $('#alphaAn'+i+'Prd'+j).val(pondProducto);
             $('#produccionAn'+i+'Prd'+j).val(prd);
             $('#produccionHiddenAn'+i+'Prd'+j).html(prd);
             
             //Costo Indirecto unitario...
             var ciUnitario;
             if(prd > 0)
             {
               ciUnitario = pondAreaNegocio * pondProducto * myJSONData["costoIndirecto"] / prd;
             }
             else
             {
                 ciUnitario = 0;
             }
             var cd = myJSONData["areasNegocio"][i]["productos"][j]["cd"];
             cdTotal += cd*prd;

             //Agregamos los valores a la tabla: [Producto, CI Unitario, CD unitario]
             data.addRows([[myJSONData["areasNegocio"][i]["productos"][j]["nombre"], cd, ciUnitario]]);
         }

         $('#totalProduccion'+i).html(Math.floor(calcularSumaDentroAn("totalProduccion"+ i, "produccion", i)) );
         $('#baseProduccion'+i).html(" ( 0 ) ");
         $('#baseProduccionHidden'+i).html(calcularSumaDentroAn("totalProduccion"+ i, "produccion", i));
         $('#totalAlpha'+i).html(calcularSumaDentroAn("totalAlpha"+ i, "alpha", i));

         dataTables[i] = data;

         var chart = new google.visualization.ComboChart(document.getElementById('chart_div_' +i));
         var view = new google.visualization.Table(document.getElementById('data_view_' +i));
         
         views[i] = view;

         var configChart = {
           title : 'Costo Unitario por Producto',
           vAxis: {title: "$ [Pesos]"},
           hAxis: {title: "Producto"},
           seriesType: "bars",
           isStacked: true
         };
         //chart.draw(data, configChart);
         charts[i] = {"chart": chart, "config": configChart};
        }

        $('#sumaAlpha').html(calcularSumaAn("alphaAnTotal"));
        $('#CIFijo').html(myJSONData["costoIndirecto"]);
        $('#CITotal').html(myJSONData["costoIndirecto"]);
        $('#CDTotal').html(cdTotal);
        $('#CDBase').html(cdTotal);

        $('#CTotal').html(calculoCostoTotal());
    

           //Registramos todos los input al cambio....
           $('#valorDifExtra').change(difExtraHandler);
           ////Suscribimos a los eventos de cambio de valor en Alpha para las An
           $('input:regex(id,alphaAn[0-9]*\\b)').change(changeSliderAnHandler);
           //alpha...
           $('input:regex(id,alphaAn[0-9]*Prd[0-9]*)').change(changeSliderHandler);
           //produccion..
           $('input:regex(id,produccionAn[0-9]*Prd[0-9]*)').change(changeProductionHandler);
       });
       }

      google.setOnLoadCallback(createGraphs);


      var numId;
      
      function display(id)
      {

            if ($('#' + id).css('display') == 'none')
            {
                numId = extractNums(id)[0];
                $('#' + id).slideDown('slow', drawGraphById);
            }
            else{
                $('#' + id).slideUp('slow');
            }
      }

      function drawGraph()
       {
        var divs = $('.chart').each(function(idx, value)
           {
                if(value.style.getPropertyValue('display') != 'none')
                {
                    charts[idx]["chart"].draw(dataTables[idx], charts[idx]["config"]);
                }
           });
       }

      function drawGraphById(idx)
       {
           if(idx == null)
           {
            charts[numId]["chart"].draw(dataTables[numId], charts[numId]["config"]);
            views[numId].draw(dataTables[numId], null);
           }
           else
           {
             charts[idx]["chart"].draw(dataTables[idx], charts[idx]["config"]);
             views[idx].draw(dataTables[idx], null);
           }
       }
       
