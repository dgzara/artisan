<h1>Stock y Estado de Maduración por Tipo de Producto (unidades)</h1>

<form action="<?php echo url_for('lote/trazabilidadProducto') ?>" method="get" id="form_trazabilidad">
</form>

<?php foreach($ramas as $rama):?>
    <table>
        <tbody>
            <tr>
                <td onclick="display_ramas('<?php echo $rama->getId() ?>');">
                    <h2><?php echo $rama->getNombre() ?></h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table id="productos<?php echo $rama->getId() ?>" style="display:none" class="one-table">
        <tbody>
            <?php foreach($productos[$rama->getId()] as $producto):?>
                <tr><td onclick="display_productos('<?php echo $producto->getId() ?>');"><h3><?php echo $producto->getNombreCompleto()?></h3></td></tr>
                <tr id="cuadrado<?php echo $producto->getId() ?>" style="display: none">
                    <td>
                        <img id="loader<?php echo $producto->getId() ?>" src="/images/loader.gif" style="vertical-align: middle; display: none" alt ="loading"/>
                        <div id="ver<?php echo $producto->getId() ?>"></div>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
<?php endforeach; ?>

<script type="text/JavaScript">
    
</script>