<?php

function eliminarbackslash($texto) {

$temp = explode('\\', $texto);
$temp = implode('', $temp);

return $temp;

}

function comprobar_email($email){
    $mail_correcto = 0;
    //compruebo unas cosas primeras
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
          //miro si tiene caracter .
          if (substr_count($email,".")>= 1){
             //obtengo la terminacion del dominio
             $term_dom = substr(strrchr ($email, '.'),1);
             //compruebo que la terminación del dominio sea correcta
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
                //compruebo que lo de antes del dominio sea correcto
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
                if ($caracter_ult != "@" && $caracter_ult != "."){
                   $mail_correcto = 1;
                }
             }
          }
       }
    }
    if ($mail_correcto)
       return 1;
    else
       return 0;
} 

function GenerarClave() 
{
	return GenerarClavePro(4,6, false, false, true);
}

function GenerarClavePro($minlength, $maxlength, $useupper, $usespecial, $usenumbers) 
{ 
/* 
Description: string str_makerand(int $minlength, int $maxlength, bool $useupper, bool $usespecial, bool $usenumbers) 
returns a randomly generated string of length between $minlength and $maxlength inclusively. 

Notes: 
- If $useupper is true uppercase characters will be used; if false they will be excluded. 
- If $usespecial is true special characters will be used; if false they will be excluded. 
- If $usenumbers is true numerical characters will be used; if false they will be excluded. 
- If $minlength is equal to $maxlength a string of length $maxlength will be returned. 
- Not all special characters are included since they could cause parse errors with queries. 

Modify at will. 
*/ 
    $charset = "abcdefghijklmnopqrstuvwxyz"; 
    if ($useupper)   $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
    if ($usenumbers) $charset .= "0123456789"; 
    if ($usespecial) $charset .= "~@#$%^*()_+-={}|][";   // Note: using all special characters this reads: "~!@#$%^&*()_+`-={}|\\]?[\":;'><,./"; 
    if ($minlength > $maxlength) $length = mt_rand ($maxlength, $minlength); 
    else                         $length = mt_rand ($minlength, $maxlength); 
    for ($i=0; $i<$length; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))]; 
    return $key; 
}

function SendMail($mail, $subject, $mensaje)
{
	$headers = "From: Pastoral Ingeniería UC <pastoral@ing.puc.cl>\n";
	$headers .= 'MIME-Version: 1.0' . " \n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . " \n";
	
	return mail($mail, $subject, $mensaje, $headers);


}


?>