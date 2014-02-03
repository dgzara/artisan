<?php

/**
 * OrdenCompra form base class.
 *
 * @method OrdenCompra getObject() Returns the current form's model object
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseOrdenCompraForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'fecha'               => new sfWidgetFormDateTime(),
      'proveedor_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Proveedor'), 'add_empty' => false)),
      'lugar_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Bodega'), 'add_empty' => false)),
      'numero'              => new sfWidgetFormInputText(),
      'condiciones'         => new sfWidgetFormTextarea(),
      'fecha_recepcion'     => new sfWidgetFormDateTime(),
      'encargado_recepcion' => new sfWidgetFormInputText(),
      'guia_despacho'       => new sfWidgetFormInputText(),
      'encargado_despacho'  => new sfWidgetFormInputText(),
      'fecha_factura'       => new sfWidgetFormDateTime(),
      'n_factura'           => new sfWidgetFormInputText(),
      'fecha_pago'          => new sfWidgetFormDateTime(),
      'forma_pago'          => new sfWidgetFormTextarea(),
      'n_documento'         => new sfWidgetFormInputText(),
      'accion'              => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'fecha'               => new sfValidatorDateTime(),
      'proveedor_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Proveedor'))),
      'lugar_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Bodega'))),
      'numero'              => new sfValidatorInteger(),
      'condiciones'         => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'fecha_recepcion'     => new sfValidatorDateTime(array('required' => false)),
      'encargado_recepcion' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'guia_despacho'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'encargado_despacho'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'fecha_factura'       => new sfValidatorDateTime(array('required' => false)),
      'n_factura'           => new sfValidatorInteger(array('required' => false)),
      'fecha_pago'          => new sfValidatorDateTime(array('required' => false)),
      'forma_pago'          => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'n_documento'         => new sfValidatorInteger(array('required' => false)),
      'accion'              => new sfValidatorString(array('max_length' => 255)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'OrdenCompra', 'column' => array('numero')))
    );

    $this->widgetSchema->setNameFormat('orden_compra[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'OrdenCompra';
  }

}
