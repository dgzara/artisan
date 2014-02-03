<?php

/**
 * Cliente form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ClienteForm extends BaseClienteForm
{
  public function configure()
  {
      unset(
      $this['created_at'], $this['updated_at'],
      $this['expires_at'], $this['is_activated']
    );



      $this->widgetSchema['tipo'] = new sfWidgetFormChoice(array(
          'choices' => array('-- Seleccione --','-- Tipos --'=>Doctrine_Core::getTable('Cliente')->getTipos()),
          'expanded' => false,
      ));

      $this->widgetSchema->setLabels(array(
          'name' => 'Nombre Comercial*',
          'razon_social' => 'Razón Social*',
          'tipo' => 'Tipo Cliente*',
          'giro' => 'Giro*',
          'rut' => 'RUT*',
          'direccion' => 'Dirección*',
          'region' => 'Región',
          'telefono' => 'Teléfono',
          'casilla' => 'Casilla Postal',
          'cel' => 'Celular'
      ));

      $this->widgetSchema['rut']->setAttribute("onchange", "checkRutField(document.form.cliente_rut.value);");

      $this->validatorSchema['direccion'] = new sfValidatorString();
      $this->validatorSchema['tipo'] = new sfValidatorChoice(array('choices' => Doctrine_Core::getTable('Cliente')->getTipos()));
      $this->validatorSchema['name'] = new sfValidatorString();
      $this->validatorSchema['razon_social'] = new sfValidatorString();
      $this->validatorSchema['giro'] = new sfValidatorString();
      $this->validatorSchema['rut'] = new sfValidatorRut();


  }
}
