<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('insumo/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? 
'?id='.$form->getObject()->getId() 
: 
'')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table class="one-table">
    <tfoot>
      <tr>
        <td  colspan="2">
            <div id="barra">
                 &nbsp;<span class="volver"><a href="<?php echo url_for('insumo/index') ?>">Volver</a></span>
                 <?php if (!$form->getObject()->isNew()): ?>
                   &nbsp;-&nbsp;<span class="eliminar"><?php echo link_to('Eliminar', 'insumo/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => '¿Está seguro en eliminar este insumo?')) ?></span>
                 <?php endif; ?>
                 &nbsp;-&nbsp;<input type="submit" value="Guardar" onclick= "return validarDatos()" />
            </div>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
</form>
<script type="text/javascript">
    var ir=true;
    $(document).ready(function() {
       document.getElementById('insumo_stock_critico').setAttribute('onchange', 'cambioStock()');
    });
    function cambioStock()
    {
       if (document.getElementById('insumo_stock_critico').value=='')
       {
           ir=false;
           alert ("el stock no puede ser nulo");
           return false;
       }
       else {ir=true;}
    }
    function validarDatos()
    {
       if (!ir){
       alert ('faltan campos por rellenar');
       }
       return ir;
    }
</script>
      

