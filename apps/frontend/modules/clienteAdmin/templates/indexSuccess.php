<?php use_helper('I18N', 'Date') ?>

    <?php include_partial('clienteAdmin/filters', array('form' => $filters, 'configuration' => $configuration)) ?>


<form action="<?php echo url_for('cliente_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('clienteAdmin/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
</form>

