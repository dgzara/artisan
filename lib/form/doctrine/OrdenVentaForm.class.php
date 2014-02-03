<?php

/**
 * OrdenVenta form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class OrdenVentaForm extends BaseOrdenVentaForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);

    $this->widgetSchema['cliente_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Clientes --'=>Doctrine_Core::getTable('Cliente')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

                $this->widgetSchema['local_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Locales --'=>Doctrine_Core::getTable('Local')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

    $this->widgetSchema->setLabels(array(
            'n_oc' => 'N° de Orden de Compra Cliente*',
            'numero' => 'N° de Orden de Venta*',
            'cliente_id' => 'Cliente*',
            'local_id' => 'Local*'
        ));

    $this->setDefault('fecha', date('Y/m/d/H:m'));
    $this->widgetSchema['fecha_envio'] = new sfWidgetFormInputText();
    $this->widgetSchema['fecha_envio']->setAttribute('type', 'hidden');
    $this->widgetSchema['fecha_envio']->setHidden(true);
    $this->widgetSchema['guia_despacho'] = new sfWidgetFormInputText();
    $this->widgetSchema['guia_despacho']->setAttribute('type', 'hidden');
    $this->widgetSchema['guia_despacho']->setHidden(true);
    $this->widgetSchema['encargado_despacho'] = new sfWidgetFormInputText();
    $this->widgetSchema['encargado_despacho']->setAttribute('type', 'hidden');
    $this->widgetSchema['encargado_despacho']->setHidden(true);
    $this->widgetSchema['fecha_bf'] = new sfWidgetFormInputText();
    $this->widgetSchema['fecha_bf']->setAttribute('type', 'hidden');
    $this->widgetSchema['fecha_bf']->setHidden(true);
    $this->widgetSchema['boleta_factura'] = new sfWidgetFormInputText();
    $this->widgetSchema['boleta_factura']->setAttribute('type', 'hidden');
    $this->widgetSchema['boleta_factura']->setHidden(true);
    $this->widgetSchema['n_bf'] = new sfWidgetFormInputText();
    $this->widgetSchema['n_bf']->setAttribute('type', 'hidden');
    $this->widgetSchema['n_bf']->setHidden(true);
    $this->widgetSchema['fecha_recepcion'] = new sfWidgetFormInputText();
    $this->widgetSchema['fecha_recepcion']->setAttribute('type', 'hidden');
    $this->widgetSchema['fecha_recepcion']->setHidden(true);
    $this->widgetSchema['encargado_recepcion'] = new sfWidgetFormInputText();
    $this->widgetSchema['encargado_recepcion']->setAttribute('type', 'hidden');
    $this->widgetSchema['encargado_recepcion']->setHidden(true);
    $this->widgetSchema['fecha_pago'] = new sfWidgetFormInputText();
    $this->widgetSchema['fecha_pago']->setAttribute('type', 'hidden');
    $this->widgetSchema['fecha_pago']->setHidden(true);
    $this->widgetSchema['forma_pago'] = new sfWidgetFormInputText();
    $this->widgetSchema['forma_pago']->setAttribute('type', 'hidden');
    $this->widgetSchema['forma_pago']->setHidden(true);
    $this->widgetSchema['n_documento'] = new sfWidgetFormInputText();
    $this->widgetSchema['n_documento']->setAttribute('type', 'hidden');
    $this->widgetSchema['n_documento']->setHidden(true);
    $this->widgetSchema['accion'] = new sfWidgetFormInputText();
    $this->widgetSchema['accion']->setAttribute('type', 'hidden');
    $this->widgetSchema['accion']->setHidden(true);
    $this->widgetSchema['accion']->setDefault('Validar');
    $this->widgetSchema['fecha'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es'));
    $this->widgetSchema['fecha']->setLabel("Fecha Creación Orden de Venta*");
    $this->widgetSchema['archivo_adjunto'] = new sfWidgetFormInputFile(array(
      'label' => 'Archivo Adjunto',
    ));
    $this->widgetSchema['archivo_adjunto2'] = new sfWidgetFormInputFile(array(
      'label' => 'Archivo Adjunto 2',
    ));
    $this->widgetSchema['archivo_adjunto3'] = new sfWidgetFormInputFile(array(
      'label' => 'Archivo Adjunto 3',
    ));
    
    $this->validatorSchema['cliente_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cliente')));
    $this->validatorSchema['numero'] = new sfValidatorInteger();
    $this->validatorSchema['n_oc'] = new sfValidatorInteger();
    $this->validatorSchema['fecha'] = new sfValidatorDate();
    $this->validatorSchema['local_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Local')));
    $this->validatorSchema['archivo_adjunto'] = new sfValidatorFile(array(
      'required'   => false,
      'path'       => sfConfig::get('sf_upload_dir').'/ov'
    ));
    $this->validatorSchema['archivo_adjunto2'] = new sfValidatorFile(array(
      'required'   => false,
      'path'       => sfConfig::get('sf_upload_dir').'/ov'
    ));
    $this->validatorSchema['archivo_adjunto3'] = new sfValidatorFile(array(
      'required'   => false,
      'path'       => sfConfig::get('sf_upload_dir').'/ov'
    ));
    
  }
}
