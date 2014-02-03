<?php

/**
 * sfGuardGroup form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardGroupForm extends PluginsfGuardGroupForm
{
  public function configure()
  {

    $this->widgetSchema['permissions_list'] = new sfWidgetFormChoice(array(
                'choices'  => Doctrine_Core::getTable('sfGuardPermission')->getPermisos(),
                'multiple' => true,
                'expanded' => true,
    ));
    $this->widgetSchema['users_list'] = new sfWidgetFormChoice(array(
                'choices'  => Doctrine_Core::getTable('sfGuardUser')->getUsuarios(),
                'multiple' => true,
                'expanded' => true,
    ));

    $this->widgetSchema['name']->setLabel('Nombre Grupo*');
    $this->widgetSchema['description']->setLabel('Descripción');
    $this->widgetSchema['users_list']->setLabel('Usuarios');
    $this->widgetSchema['permissions_list']->setLabel('Permisos');

    unset(
                 $this['created_at'],
                 $this['updated_at']
            );

    $this->validatorSchema['name'] = new sfValidatorString(array(), array('required' => "Campo Obligatorio."));


  }
}
