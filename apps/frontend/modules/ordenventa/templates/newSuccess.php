<h1>Crear Orden de Venta</h1>

<?php include_partial('form', array('form' => $form, 'orden_venta_productos' => null, 'boton_guardar' => 'Crear')) ?>

<script type="text/javascript">
    $(document).ready(function() {
        cambiarCliente();
    });
</script>