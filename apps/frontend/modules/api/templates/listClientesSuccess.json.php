 <?php 
$callback = $_REQUEST['callback'];
if ($callback) {
    echo $callback . '(' ;
} 
?> 
[
<?php $nb = count($clientes); $i = 0; foreach ($clientes as $url => $cliente): ++$i ?>
{

<?php $nb1 = count($cliente); $j = 0; foreach ($cliente as $key => $value): ++$j ?>
  "<?php echo $key ?>": <?php echo json_encode($value).($nb1 == $j ? '' : ',') ?>

<?php endforeach ?>
}<?php echo $nb == $i ? '' : ',' ?>

<?php endforeach ?>
]

 <?php 
if ($callback) 
{
    echo ');';
} 
?> 