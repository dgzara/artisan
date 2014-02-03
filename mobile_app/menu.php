<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  //remove all the variables in the session 
 session_unset(); 

 // destroy the session 
 session_destroy();
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
$colname_nombre = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_nombre = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}

?>

<?php require_once('Connections/localhost.php'); ?>
<?php
    
    mysql_select_db($database_localhost, $localhost);
    $query_versiones = "SELECT id, nombre FROM versiones ORDER BY nombre ASC";
    $versiones = mysql_query($query_versiones, $localhost) or die(mysql_error());
    $row_versiones = mysql_fetch_assoc($versiones);
    $totalRows_versiones = mysql_num_rows($versiones);
    
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Artisan Mobile</title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal97.css" rel="stylesheet" type="text/css" />
<!-- TinyMCE -->
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example word content CSS (should be your site CSS) this one removes paragraph margins
		content_css : "css/word.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->
</head>

<body>
<div align="center"><h2><b>Descarga de Aplicaci&oacute;n m&oacute;vil Artisan</b></h2></div>
<div>
<table width="592" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="590" border="0" cellpadding="0" cellspacing="4">
      <tr>
        <td>Bienvenido <b><?php echo utf8_encode($_SESSION['nombre']); ?></b> | <a href="menu.php" class="link"><b>Inicio</b></a></td>
        <td align="right"><a href="<?php echo $logoutAction ?>" class="link"><b>Desconectar</b></a></td>
      </tr>
	<tr>
	    <td align="center"><?php /*include "menunoti.php";*/ ?></td>
	  </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" ></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<form name="descargar" method="post" action="enviar.php">
<table align="center">
	
	<tr>
		<td>
Elija la versi&oacute;n a descargar:
		</td>
		<td>
<select name="versiones" id="versiones">
<option selected="selected" value="">Seleccione..</option>
<?php
    do {  
    ?>
<option value="<?php echo $row_versiones['id']?>"><?php echo ($row_versiones['nombre']); ?></option>
<?php
    } while ($row_versiones = mysql_fetch_assoc($versiones));
    $rows = mysql_num_rows($versiones);
    if($rows > 0) {
        mysql_data_seek($versiones, 0);
        $row_versiones = mysql_fetch_assoc($versiones);
    }
    ?>
</select>
		</td>
	</tr>
</table>
<table align="center">
	
	<tr>
		<td align="center">
			<input type="submit" value="Descargar" class="waQueryBoxInputBoton" name="submit" />
		</td>
	</tr>
</table>
</form>
</div>
</body>
</html>