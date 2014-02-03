
<?php $colores= array("#EEEEEE", "#CDCDCD", "#A6A6A6");?>
<?php $i=0; ?>
    <div class="tb-topleft">
        <?php foreach ($opts as $zone => $buttons): ?>
        <div align="left" style="background-color:rgb(150,181,12); color:whitesmoke;-moz-border-radius: 10px; border-radius: 10px; padding: 5px 10px; float: left; margin-right: 20px;">
            <b><?php echo $zone;?></b><br>
            <?php foreach ($buttons as $label => $url): ?>
                <?php foreach ($permisos as $label2 => $nombre_permiso): ?>
                <?php if($label==$label2):?>
                    <?php if($sf_user->hasPermission($nombre_permiso)):?>
                    <?php //if(true):?>
                        <?php if ($seleccionar == $label): ?><b><?php endif;?>
                        <p class="compact-icon-button<?php if ($seleccionar == $label): ?>2<?php endif;?>
                           <?php if(strstr($url, 'new')):?>add-icon
                           <?php elseif(strstr($url, 'ordencompra/index')):?>ciclo-icon
                           <?php elseif(strstr($url, 'ordenventa/index')):?>ciclo-icon
                         <?php elseif(strstr($url, 'ordenventa/upload')):?>add-icon
                           <?php elseif(strstr($url, 'captura/alertas')):?>alertas-icon
                           <?php elseif(strstr($url, 'captura/estadisticas')):?>estadisticas-icon
                           <?php elseif(strstr($url, 'lote/index')):?>ciclo-icon
                           <?php elseif(strstr($url, 'index')):?>list-icon
                           <?php elseif(strstr($url, 'factura')):?>list-icon
                           <?php elseif(strstr($url, 'productosElaborados')):?>list-icon
                           <?php elseif(strstr($url, 'user')):?>user-icon
                           <?php endif;?>" style="-moz-border-radius: 5px; border-radius: 5px;">
                           <?php echo link_to($label, $url) ?>
                        </p>
                        <?php if ($seleccionar == $label): ?></b><?php endif;?>
                     <?php endif;?>

                <?php endif;?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        <?php $i++; ?>
        <?php endforeach; ?>
    </div>
