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
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Plantilla para env&iacute;o de Mail Masivo</title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal97.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center"><h2><b>Mail Masivo</b></h2></div>
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
<?php
    
    // block any attempt to the filesystem
    if (isset($_POST['versiones']) && basename($_POST['versiones']) == $_POST['versiones']) {
        
        $filename = $_POST['versiones'];
        
    } else {
        
        $filename = NULL;
        
    } 
    // define error message
    $err = '<p style="color:#990000">El archivo que desea no se encuentra disponible</p>';
    
    if (!$filename) {
        
        // if variable $filename is NULL or false display the message
        echo $err;
        
    } else {
        
        // define the path to your download folder plus assign the file name
        $path = 'downloads/'.$filename;
        
        // check that file exists and is readable
        if (file_exists($path) && is_readable($path)) {
            
            // get the file size and send the http headers
            $size = filesize($path);
            header('Content-Type: application/octet-stream');
            header('Content-Length: '.$size);
            header('Content-Disposition: attachment; filename='.$filename);
            header('Content-Transfer-Encoding: binary');
            
            // open the file in binary read-only mode
            // display the error messages if the file can«t be opened
            $file = @ fopen($path, 'rb');
            
            if ($file) {
                
                // stream the file and exit the script when complete
                fpassthru($file);
                exit;
                
            } else {
                
                echo $err;
                
            }
            
        } else {
            
            echo $err;
            
        }
        
    }
    
    ?> 
</div>
</body>
</html>
