<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version5 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('registro', 'usuario_nombre', 'string', '500', array(
             'notnull' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('registro', 'usuario_nombre');
    }
}