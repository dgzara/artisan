<h1>Detalles de Plantilla de Pauta de Elaboración N°<?php echo $plantilla_pauta->getId() ?></h1>

<table class="one-table">
  <tbody>

    <tr>
      <th>Nombre:</th>
      <td><?php echo $plantilla_pauta->getNombre() ?></td>
    </tr>
    <tr>
      <th>Área de Negocios:</th>
      <td><?php echo $plantilla_pauta->getRama()->getNombre() ?></td>
    </tr>
    <tr>
        <th>Columnas</th>
        <td><ul><?php foreach($plantilla_pauta->getColumnas() as $columa):?>
            <li><?php echo $columa->getNombre();?></li>
            <?php endforeach;?>
            </ul>
        </td>
    </tr>
  </tbody>
</table>
<table class="one-table">
    <tbody>
        <h2>Ingredientes</h2>
        <table class="one-table">
            <thead>
                <th>Ingrediente</th>
                <th>Cantidad Requerida</th>
            </thead>
            <tbody>
                <?php foreach($plantilla_pauta->getPlantillaIngredientes() as $ingrediente):?>
                    <tr>
                        <td><?php echo $ingrediente->getInsumo()->getNombreCompleto() ?></td>
                        <td><?php echo $ingrediente->getCantidad() ?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <br>

        <h2>Etapas</h2>
        <?php $i = 0 ?>
        <?php foreach($plantilla_etapas as $plantilla_etapa):?>

            <tr>
                <table class="one-table">
                    <thead>
                        <tr><th>Etapa: <?php echo $plantilla_etapa->getNombre()?></th> </tr>
                        <tr><th>Instrucciones</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($plantilla_instrucciones[$i] as $plantilla_instruccion): ?>
                            <tr>
                                <td><?php echo $plantilla_instruccion->getDescripcion() ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </tr>
            <?php $i++ ?>
        <?php endforeach?>
    </tbody>
</table>

<div id="barra">
    <?php if($sf_user->hasPermission("Ver_Administracion_Pautas_EditarPlantillas")):?>
        <span class="modificar"><a href="<?php echo url_for('plantilla_pauta/edit?id='.$plantilla_pauta->getId()) ?>">Modificar</a></span>
    &nbsp;-&nbsp;
    <?php endif;?>
        <span class="volver"><a href="<?php echo url_for('plantilla_pauta/index') ?>">Volver</a></span>
</div>