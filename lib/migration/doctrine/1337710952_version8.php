<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version8 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('lote', 'padre', 'integer', '8', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('lote', 'padre');
    }
}