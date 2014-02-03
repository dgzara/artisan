<div class="filtro_fecha">
    <form name="form_filtro_fecha" method="post" action=<?php echo $action;?>>
                <table class="one-table">
                    <td>Desde</td><td><input type="text" name="desde" id="filtrar_desde" readonly="true" value="<?php echo $from?>"/></td>
                    <td>Hasta</td><td><input type="text" name="hasta" id="filtrar_hasta" readonly="true" value="<?php echo $to?>"/></td>
                    <td><input type="submit" value="Filtrar"/></td>
                </table>
    </form>
</div>