<div align="right">

<form name="form" method="post" action="buscar">
   <input type="text" value="" name="value"/>
   <input type="submit" value="Filtrar"/>
</form>
</div>
<br>
<table class="one-table">
  <thead>
       <tr><td colspan="11"><h1>Buscando de proveedor</h1></td><td><a href="<?php echo url_for('proveedor/index') ?>"><img name="back" src="/images/back.png" alt="Volver" border="0"></td></tr>

<?php include('listSuccess.php'); ?>

       <hr>

       