<div id="main-menu-w">
    <ul class="clear-float" id="main-menu">
        <?php foreach($menu as $label => $url):?>
            <!-- Sólo me mostrará las pestañas en caso de tener los permisos de ver esa área -->
            <?php if($sf_user->hasPermission("Ver_".$label."")):?>
            <?php //if(true):?>
        <li
               <?php if($label == $current):?>
                    class="current"
               <?php endif;?>>
               <?php echo link_to($label, $url) ?></li>
            <?php endif;?>
        <?php endforeach;?>
    </ul>
</div><!-- /main-menu-w -->

               <?php if(!is_null($opts)):?>
                    <ul class="menu-submenu" id="NoVerMenu2">
                        <?php foreach ($opts as $label => $url): ?>
                        <?php foreach ($permisos as $label2 => $nombre_permiso): ?>
                        <?php if($label==$label2):?>

                            <?php if($sf_user->hasPermission($nombre_permiso)):?>
                            <?php //(true):?>
                            <li>
                                <a href="<?php echo url_for($url, true) ?>"
                                   <?php if($label == $seleccionar):?>
                                   class="selected"
                                   <?php endif;?>
                                   ><?php echo $label ?></a>
                            </li>
                            <?php endif;?>
                        <?php endif;?>
                        <?php endforeach ?>
                        <?php endforeach ?>
                    </ul>
                <?php endif;?>

