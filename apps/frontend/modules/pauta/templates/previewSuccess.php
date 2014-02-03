<table class="one-table">
    <thead>
        <th>Lote</th>
        <th>Cantidad</th>
        <th>Comentarios</th>
    </thead>
    <tbody>
        <?php foreach($lotes as $lote):?>
            <tr>
                <td width="30%"><?php echo $lote->getProducto()->getNombreCompleto() ?></td>
                <td width="10%"><?php echo $lote->getCantidad() ?></td>
                <td><?php echo $lote->getComentarios() ?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
