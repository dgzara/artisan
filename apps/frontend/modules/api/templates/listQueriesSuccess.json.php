 <?php 
$callback = $_REQUEST['callback'];
if ($callback) {
    echo $callback . '(' ;
} 
?> 
[
<?php $nb = count($objetos); $i = 0; foreach ($objetos as $url => $objeto): ++$i ?>
{

<?php $nb1 = count($objeto); $j = 0; foreach ($objeto as $key => $value): ++$j ?>
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