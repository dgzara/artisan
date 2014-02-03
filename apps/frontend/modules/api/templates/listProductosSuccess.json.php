 <?php 
$callback = $_REQUEST['callback'];
if ($callback) {
    echo $callback . '(' ;
} 
?> 
[
<?php $nb = count($productos); $i = 0; foreach ($productos as $url => $producto): ++$i ?>
{

<?php $nb1 = count($producto); $j = 0; foreach ($producto as $key => $value): ++$j ?>
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
