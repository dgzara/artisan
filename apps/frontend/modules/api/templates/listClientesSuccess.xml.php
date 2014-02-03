<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>
<clientes>
<?php foreach ($clientes as $url => $cliente): ?>
  <cliente url="<?php echo $url ?>">
<?php foreach ($cliente as $key => $value): ?>
    <<?php echo $key ?>><?php echo $value ?></<?php echo $key ?>>
<?php endforeach; ?>
  </cliente>
<?php endforeach; ?>
</clientes>