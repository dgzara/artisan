<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MM</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
</script>
<script src="rut.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
	
	   	function olvido() {
			ajaxUrl("olvidopassword.php");
			return false;
		}
		
		function password() {
			if (document.getElementById('rutx').value != "") {
			rutx = document.getElementById('rutx').value;
			}
			else {
					alert('Debe ingresar su Rut');
					return false;
				}
				
				ajaxUrl("recuperapassword.php?rutx="+rutx);
			return false;
		}
		
		function envia() {
			if (document.getElementById('rut').value != "") {
			rut = document.getElementById('rut').value;
			}
			else {
					alert('Debe ingresar su Rut');
					return false;
				}
			if (document.getElementById('password').value != "") {
			password = document.getElementById('password').value;
			}
			else {
					
		            alert('Debe ingresar su Password');
					return false;
				}
				
				ajaxUrl("valida.php?rut="+rut+"&password="+password);
			return false;
		}

		function ajaxUrl(nuevaUrl) {

			http_request = false;

			if (window.XMLHttpRequest) { // Mozilla, Safari,...
				http_request = new XMLHttpRequest();
				if (http_request.overrideMimeType) {
					http_request.overrideMimeType('text/xml');
				}
			} else if (window.ActiveXObject) { // IE
				try {
					http_request = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try {
						http_request = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e) {}
				}
			}

			if (!http_request) {
				alert('Falla :( No es posible crear una instancia XMLHTTP');
				return false;
			}
			http_request.onreadystatechange = alertContents;
			http_request.open('GET', nuevaUrl, true);
			http_request.send(null);

		}

		function alertContents() {

			if (http_request.readyState == 1) {
			document.getElementById('contenido').innerHTML = "<div align='center'><br />Cargando..</div>";
			}
			if (http_request.readyState == 4) {
				if (http_request.status == 200) {
					if (http_request.responseText == "ok") {
					var ruta = "menu.php";
					var campos=ruta;
 					window.location.href = unescape(campos);
					}
					else {
					document.getElementById('contenido').innerHTML = http_request.responseText;
					}
				} else {
					alert('Hubo problemas con la peticion.');
				}
			}

		}
	</script>
</head>

<body>
<div align="center"><h2><b>Descarga de aplicación móvil Artisan</b></h2></div>
<table width="140" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center"><p><br>
                        <img src="logoadmin.png" ></p>
                      <form name="form1"><table width="108" border="0" align="center" cellpadding="0" cellspacing="3">
              <tr>
                <td width="108">Rut : <br />
                  <input name="rut" type="text" id="rut" onBlur="checkRutField(this);" maxlength="12"/></td>
                </tr>
              <tr>
                <td>Password :<br /><input name="password" type="password" id="password" /></td>
                </tr>
              <tr>
                <td align="center"><br /><input name="button" onClick="javascript:return envia();" type="submit" id="button" value="Ingresar" /></td>
                </tr>
<tr> 
                <td>&nbsp;</td>
              </tr>              
<tr> 
                <td id="contenido"></td>
              </tr>
            </table></form></td>
                  </tr>
</table>
</body>
</html>