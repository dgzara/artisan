<script type="text/javascript">
    $(document).ready(function()
    {
        runValidationsOrdenVenta()
    });
</script>
<h1>Validar Orden de Venta</h1>
<br>

<?php include_partial('form', array('form' => $form, 'orden_venta_productos' => $orden_venta_productos, 'boton_guardar' => 'Validar', 'accion' => 'validate')) ?>

