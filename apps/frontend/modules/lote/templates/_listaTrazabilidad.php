<table>
    <?php
        $fecha_maduracion = $producto->getMaduracion();
        $fechas = $producto->getFechas();
    ?>
    <tr>
        <th>Nº Días</th>
        <?php foreach($fechas as $fecha):?>
            <th><?php echo date('d M Y', strtotime($fecha)) ?></th>
        <?php endforeach;?>
    </tr>
    <?php for($k = 0; $k < count($fechas) - 6; $k++):?>
    <tr
        <?php if($k == 0):?>
            style="background: #E6FF99;"
        <?php endif;?>
        >
        <td><?php echo ($fecha_maduracion * $k) ?> días</td>
        <?php for($j = 0; $j < count($fechas); $j++):?>
            <td
                <?php if($j == 4):?>
                    style="background: #E6FF99;"
                <?php endif;?>
                ><?php echo $producto->getProduccion(($fecha_maduracion * $k), $fechas[$j]); ?></td>
        <?php endfor; ?>
    </tr>
    <?php endfor;?>
</table>