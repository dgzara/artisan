<?php

/**
 * OrdenCompra form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class OrdenCompraForm extends BaseOrdenCompraForm {

    public function configure() {
        unset(
                $this['created_at'], $this['updated_at']
        );

        $this->widgetSchema['proveedor_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Proveedores --'=>Doctrine_Core::getTable('Proveedor')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

        $this->widgetSchema['lugar_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Lugares --'=>Doctrine_Core::getTable('Bodega')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));

        $this->widgetSchema->setLabels(array(
            'numero' => 'Número de O.C.*',
            'lugar' => 'Lugar *',
            'n_factura' => 'Número de Factura',
            'forma_pago' => 'Forma de Pago',
            'fecha_factura' => 'Fecha de Factura',
            'proveedor_id' => 'Proveedor*',
            'condiciones' => 'Condiciones Comerciales y Especificaciones Técnicas',
            'n_documento' => 'Número de Documento'


        ));

        $this->setDefault('fecha', date('Y/m/d/H:m'));
        $this->widgetSchema['fecha_factura'] = new sfWidgetFormInputText();
        $this->widgetSchema['fecha_factura']->setAttribute('type', 'hidden');
        $this->widgetSchema['fecha_factura']->setHidden(true);
        $this->widgetSchema['n_factura'] = new sfWidgetFormInputText();
        $this->widgetSchema['n_factura']->setAttribute('type', 'hidden');
        $this->widgetSchema['n_factura']->setHidden(true);
        $this->widgetSchema['guia_despacho'] = new sfWidgetFormInputText();
        $this->widgetSchema['guia_despacho']->setAttribute('type', 'hidden');
        $this->widgetSchema['guia_despacho']->setHidden(true);
        $this->widgetSchema['encargado_despacho'] = new sfWidgetFormInputText();
        $this->widgetSchema['encargado_despacho']->setAttribute('type', 'hidden');
        $this->widgetSchema['encargado_despacho']->setHidden(true);
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
        $this->widgetSchema['fecha'] = new sfWidgetFormJQueryDate(array(
                    'date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')),
                    'culture' => 'es'
                ));
        $this->widgetSchema['fecha']->setLabel('Fecha de Emisión*') ;
        $this->widgetSchema['fecha_recepcion']->setLabel('Fecha de Recepción*') ;
        $this->widgetSchema['fecha_factura']->setLabel('Fecha de Facturación*') ;
        $this->widgetSchema['archivo_adjunto'] = new sfWidgetFormInputFile(array(
          'label' => 'Archivo Adjunto',
        ));
        $this->widgetSchema['archivo_adjunto2'] = new sfWidgetFormInputFile(array(
          'label' => 'Archivo Adjunto',
        ));
        $this->widgetSchema['archivo_adjunto3'] = new sfWidgetFormInputFile(array(
          'label' => 'Archivo Adjunto',
        ));
         
         
         $this->validatorSchema['proveedor_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Proveedor')));
         $this->validatorSchema['numero'] = new sfValidatorInteger();
         $this->validatorSchema['fecha'] = new sfValidatorDate();
         $this->validatorSchema['archivo_adjunto'] = new sfValidatorFile(array(
          'required'   => false,
          'path'       => sfConfig::get('sf_upload_dir').'/oc'
        ));
         $this->validatorSchema['archivo_adjunto2'] = new sfValidatorFile(array(
          'required'   => false,
          'path'       => sfConfig::get('sf_upload_dir').'/oc'
        ));
         $this->validatorSchema['archivo_adjunto3'] = new sfValidatorFile(array(
          'required'   => false,
          'path'       => sfConfig::get('sf_upload_dir').'/oc'
        ));
    }

}
