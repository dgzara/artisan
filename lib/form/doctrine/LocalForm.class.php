<?php

/**
 * Local form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class LocalForm extends BaseLocalForm
{
  public function configure()
  {
      unset(
      $this['created_at'], $this['updated_at'],
      $this['expires_at'], $this['is_activated'], $this['codigo']
    );


       $this->setDefault('codigo', Doctrine_Core::getTable('Local')->getLastNumero());

       $this->widgetSchema['region'] = new sfWidgetFormChoice(array('choices' => Doctrine_Core::getTable('Local')->getRegiones(),'multiple' => false, 'expanded' => false));
  
       $this->widgetSchema['cliente_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Clientes --'=>Doctrine_Core::getTable('Cliente')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

       $this->widgetSchema->setLabels(array(
          'nombre' => 'Local*',
          'codigo' => 'Código',
          'direccion' => 'Dirección',
          'region' => 'Región',
          'telefono' => 'Teléfono',
          'email_1' => 'E-Mail 1',
          'email_2' => 'E-Mail 2',
          'cliente_id' => 'Cliente*',
          'cel_1' => 'Celular 1',
          'cel_2' => 'Celular 2'
        ));

       $this->validatorSchema['nombre'] = new sfValidatorString(array(),array('required' => 'Campo Obligatorio. '));
       
       $this->validatorSchema['cliente_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cliente')), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un cliente.'));

       $this->validatorSchema['cliente_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cliente')), array('required' => 'Debe llenar este campo.', 'invalid' => 'Debe seleccionar un cliente.'));

       $this->validatorSchema['email_1'] = new sfValidatorEmail(array('required' => false), array('invalid' => "Debe ser un Email válido"));
       $this->validatorSchema['email_2'] = new sfValidatorEmail(array('required' => false), array('invalid' => "Debe ser un Email válido"));

       
  }
}
