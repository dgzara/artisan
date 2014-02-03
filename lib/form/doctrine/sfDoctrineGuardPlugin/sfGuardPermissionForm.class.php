<?php

/**
 * sfGuardPermission form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardPermissionForm extends PluginsfGuardPermissionForm
{
  public function configure()
  {

         //Funcionando para permisos
    unset($this['created_at'], $this['updated_at']);
    $this->widgetSchema['name']->setLabel('Nombre');
    $this->widgetSchema['description']->setLabel('Descripción');
    
    

    $this->widgetSchema['groups_list'] = new sfWidgetFormChoice(array(
                'choices'  => Doctrine_Core::getTable('sfGuardGroup')->getGrupos(),
                'multiple' => true,
                'expanded' => true,
    ));
    $this->widgetSchema['users_list'] = new sfWidgetFormChoice(array(
                'choices'  => Doctrine_Core::getTable('sfGuardUser')->getUsuarios(),
                'multiple' => true,
                'expanded' => true,
    ));
    $this->widgetSchema['users_list']->setLabel('Usuarios');
    $this->widgetSchema['groups_list']->setLabel('Grupos');

  }
}
