function checkCDV( dvr )
{
  dv = dvr + "";
  if ( dv != '0' && dv != '1' && dv != '2' && dv != '3' && dv != '4' && dv != '5' && dv != '6' && dv != '7' && dv != '8' && dv != '9' && dv != 'k'  && dv != 'K')
  {
    alert("Debe ingresar un digito verificador valido.");
    return false;
  }
  return true;
}


function checkDV( crut )
{
  var error = false; 
  largo = crut.length;
  if ( largo < 2 )
  {
	error = true;
  }
  if ( largo > 2 )
    rut = crut.substring(0, largo-1);

  dv = crut.charAt(largo-1);
  checkCDV( dv );

  if ( rut == null || dv == null )
      return 0;

  var dvr = '0';

  suma = 0;
  mul  = 2;

  for (i= rut.length-1  ; i >= 0; i--)
  {
    suma = suma + rut.charAt(i) * mul;
    if (mul == 7)
      mul = 2;
    else    
      mul++;
  }
  res = suma % 11;
  if (res==1)
    dvr = 'k';
  else if (res==0)
    dvr = '0';
  else
  {
    dvi = 11-res;
    dvr = dvi + "";
  }
  if ( dvr != dv.toLowerCase() )
  {
	error = true;
  }
  if(error) {
	return false;
  }
  else {
	return true;
  } 
}

function checkRutField(camtexto)
{
  var error = false;
  texto = camtexto.value;
  if(texto == "") {
	return true;
  }
  var tmpstr = "";
  for ( i=0; i < texto.length ; i++ )
    if ( texto.charAt(i) != ' ' && texto.charAt(i) != '.' && texto.charAt(i) != '-' )
      tmpstr = tmpstr + texto.charAt(i);
  texto = tmpstr;
  largo = texto.length;

  if ( largo > 0 && largo < 2 )
  {
    error = true;
  }
 for (i=0; i < largo ; i++ )
  { 
    if ( texto.charAt(i) !="0" && texto.charAt(i) != "1" && texto.charAt(i) !="2" && texto.charAt(i) != "3" && texto.charAt(i) != "4" && texto.charAt(i) !="5" && texto.charAt(i) != "6" && texto.charAt(i) != "7" && texto.charAt(i) !="8" && texto.charAt(i) != "9" && texto.charAt(i) !="k" && texto.charAt(i) != "K" ) 
    {
      error = true;
    }
  }
  var invertido = "";
  for ( i=(largo-1),j=0; i>=0; i--,j++ )
    invertido = invertido + texto.charAt(i);

  var dtexto = "";
  dtexto = dtexto + invertido.charAt(0);
  if(dtexto != "") {
  	dtexto = dtexto + '-';
  }
  cnt = 0;

  for ( i=1,j=2; i<largo; i++,j++ )
  {
    if ( cnt == 3 )
    {
      dtexto = dtexto + '.';
      j++;
      dtexto = dtexto + invertido.charAt(i);
      cnt = 1;
    }
    else
    { 
      dtexto = dtexto + invertido.charAt(i);
      cnt++;
    }
  }
  invertido = "";
  for ( i=(dtexto.length-1),j=0; i>=0; i--,j++ )
    invertido = invertido + dtexto.charAt(i);


  camtexto.value = invertido;  
  if(texto.length > 7 ) {
  	if ( !checkDV(texto) ) {
		error = true;
  	}
  }
  else {
	error = true;
  }
		
		
  if(error) {
	alert("El dato ingresado no es un R.U.T valido.");
	camtexto.focus();
	camtexto.select();
	return false;
  }
  else {
  	return true;
  }
  
  
}