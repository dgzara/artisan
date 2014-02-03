<?php use_javascript('habilitandoDisabled.js') ?>
<script type="text/javascript">
    $(document).ready(function()
    {
        runValidationsOrdenCompra()
    });
</script>
<h1 >Validar Orden de Compra</h1>
<label style="color:red"><?php if($vacio=='true') {echo '*Error: No puede ingresar una orden vacía';}?></label>
<?php include_partial('validarform', array('form' => $form, 'orden_compra_insumos' => $orden_compra_insumos)) ?>

