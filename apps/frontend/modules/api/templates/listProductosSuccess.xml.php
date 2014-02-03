<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>
<productos>
<?php foreach ($productos as $url => $producto): ?>
  <producto url="<?php echo $url ?>">
<?php foreach ($producto as $key => $value): ?>
    <<?php echo $key ?>><?php echo $value ?></<?php echo $key ?>>
<?php endforeach; ?>
  </producto>
<?php endforeach; ?>
</productos>