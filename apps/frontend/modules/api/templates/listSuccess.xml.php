<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>
<objetos>
<?php foreach ($objetos as $url => $objeto): ?>
  <objeto url="<?php echo $url ?>">
<?php foreach ($objeto as $key => $value): ?>
    <<?php echo $key ?>><?php echo $value ?></<?php echo $key ?>>
<?php endforeach; ?>
  </objeto>
<?php endforeach; ?>
</objetos>