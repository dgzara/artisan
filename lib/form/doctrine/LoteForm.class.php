<?php

/**
 * Lote form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class LoteForm extends BaseLoteForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at'], $this['padre']);

      $this->widgetSchema['producto_id'] = new sfWidgetFormChoice(array(
                    'choices' => Doctrine_Core::getTable('Producto')->getNombresInput(),
                   'multiple' => false, 'expanded' => false
                ));
    
//      $this->widgetSchema['cantidad_actual'] = new sfWidgetFormInputText();
//      $this->widgetSchema['cantidad_actual']->setAttribute('type', 'hidden');
//      $this->widgetSchema['cantidad_actual']->setHidden(true);

      $this->widgetSchema['pauta_id'] = new sfWidgetFormInputText();
      $this->widgetSchema['pauta_id']->setAttribute('type', 'hidden');
      $this->widgetSchema['pauta_id']->setHidden(true);

      // Producto
      //$this->widgetSchema['producto_id'] = new sfWidgetFormInputText();
      //$this->widgetSchema['producto_id']->setAttribute('type', 'hidden');
      //$this->widgetSchema['producto_id']->setHidden(true);

      // Número
      //$this->widgetSchema['numero'] = new sfWidgetFormInputText();
      //$this->widgetSchema['numero']->setAttribute('type', 'hidden');
      //$this->widgetSchema['numero']->setHidden(true);


      $this->widgetSchema['fecha_entrada']->setDefault(date('Y/m/d/H:m'));
      $this->widgetSchema['n_documento']->setHidden(true);
      $this->widgetSchema['n_documento']->setAttribute('type', 'hidden');
      $this->widgetSchema['cc_santiago']->setHidden(true);
      $this->widgetSchema['cc_santiago']->setAttribute('type', 'hidden');
      $this->widgetSchema['cc_valdivia']->setHidden(true);
      $this->widgetSchema['cc_valdivia']->setAttribute('type', 'hidden');
      $this->widgetSchema['medio_transporte']->setHidden(true);
      $this->widgetSchema['medio_transporte']->setAttribute('type', 'hidden');
      $this->widgetSchema['accion']->setHidden(true);
      $this->widgetSchema['accion']->setAttribute('type', 'hidden');
      $this->widgetSchema['cantidad_danada']->setHidden(true);
      $this->widgetSchema['cantidad_danada']->setAttribute('type', 'hidden');
      $this->widgetSchema['cantidad_ff']->setHidden(true);
      $this->widgetSchema['cantidad_ff']->setAttribute('type', 'hidden');
      $this->widgetSchema['fecha_entrada'] = new sfWidgetFormInputText();
      $this->widgetSchema['fecha_entrada']->setAttribute('type', 'hidden');
      $this->widgetSchema['fecha_entrada']->setHidden(true);
      $this->widgetSchema['fecha_salida'] = new sfWidgetFormInputText();
      $this->widgetSchema['fecha_salida']->setHidden(true);
      $this->widgetSchema['fecha_salida']->setAttribute('type', 'hidden');
      $this->widgetSchema['fecha_empaque'] = new sfWidgetFormInputText();
      $this->widgetSchema['fecha_empaque']->setHidden(true);
      $this->widgetSchema['fecha_empaque']->setAttribute('type', 'hidden');
      $this->widgetSchema['fecha_envio'] = new sfWidgetFormInputText();
      $this->widgetSchema['fecha_envio']->setHidden(true);
      $this->widgetSchema['fecha_envio']->setAttribute('type', 'hidden');
      $this->widgetSchema['fecha_recepcion'] = new sfWidgetFormInputText();
      $this->widgetSchema['fecha_recepcion']->setHidden(true);
      $this->widgetSchema['fecha_recepcion']->setAttribute('type', 'hidden');
      $this->widgetSchema['cantidad_danada_stgo']->setHidden(true);
      $this->widgetSchema['cantidad_danada_stgo']->setAttribute('type', 'hidden');
      $this->widgetSchema['cantidad_ff_stgo']->setHidden(true);
      $this->widgetSchema['cantidad_ff_stgo']->setAttribute('type', 'hidden');
      $this->widgetSchema['cantidad_recibida']->setHidden(true);
      $this->widgetSchema['cantidad_recibida']->setAttribute('type', 'hidden');


//      $this->widgetSchema['fecha_elaboracion'] = new sfWidgetFormJQueryDate(array('date_widget' => new sfWidgetFormI18nDate(array('culture' => 'es')), 'culture' => 'es', 'default' => date('d/M/Y')));

      $this->widgetSchema->setLabels(array(
          'numero' => 'Número','fecha_entrada' => 'Fecha de Ingreso a Cámara de Maduración*','fecha_salida' => 'Fecha de Retiro de Cámara de Maduración*',
          'fecha_envio' => 'Fecha de Despacho a Centro de Distribución*','fecha_empaque' => 'Fecha de Empaque*','fecha_elaboracion' => 'Fecha de Elaboración',
          'cantidad_danada' => 'Unidades No Consumibles Valdivia*', 'cantidad_danada_stgo' => 'Unidades No Consumibles Santiago*', 'cantidad' => 'Unidades Producidas',
          'cantidad_ff' => 'Unidades Fuera de Formato Valdivia*', 'cantidad_ff_stgo' => 'Unidades Fuera de Formato Santiago*', 'cc_santiago' => 'Stock para Control de Calidad Santiago*',
          'cc_valdivia' => 'Stock para Control de Calidad Valdivia*','n_documento' => 'N° de Documento de Despacho', 'cantidad_recibida' => 'Cantidad Recibida en Santiago*',
          'medio_transporte' => 'Empresa de Transporte*','fecha_recepcion' => 'Fecha de Recepción en Centro de Distribución*', 'accion' => 'Acción'
        ));

      $this->validatorSchema['producto_id'] = new sfValidatorInteger(array('required' => false));
      $this->validatorSchema['n_documento'] = new sfValidatorInteger(array('required' => false));
      $this->validatorSchema['pauta_id'] = new sfValidatorInteger(array('required' => false));
      //$this->validatorSchema['cantidad_danada'] = new sfValidatorInteger(array('required' => "Campo Obligatorio."));
      //$this->validatorSchema['cantidad_ff'] = new sfValidatorInteger(array('required' => false));
      

  }


}
