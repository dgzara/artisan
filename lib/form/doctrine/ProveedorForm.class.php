<?php

/**
 * Proveedor form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProveedorForm extends BaseProveedorForm
{
  public function configure()
  {
      unset(
         $this['created_at'], $this['updated_at']
        );
      
      $this->widgetSchema->setLabels(array(
          'empresa_nombre' => 'Razón Social*',
          'empresa_rut' => 'RUT',
          'empresa_telefono' => 'Teléfono',
          'empresa_fax' => 'Fax',
          'empresa_giro' => 'Giro',
          'empresa_comuna' => 'Comuna',
          'empresa_direccion' => 'Dirección',
          'empresa_region' => 'Región',
          'empresa_casilla_postal' => 'Casilla Postal',
          'ventas_nombre' => 'Nombre Contacto Comercial',
          'ventas_telefono' => 'Teléfono Contacto Comercial',
          'ventas_celular' => 'Celular Contacto Comercial',
          'ventas_email' => 'E-Mail Contacto Comercial',
          'contabilidad_nombre' => 'Nombre Contacto Contabilidad',
          'contabilidad_telefono' => 'Teléfono Contacto Contabilidad',
          'contabilidad_celular' => 'Celular Contacto Contabilidad',
          'contabilidad_email' => 'E-Mail Contacto Contabilidad'
        ));

      $this->widgetSchema['empresa_rut']->setAttribute("onchange", "checkRutField(document.form.proveedor_empresa_rut.value);");

      $this->validatorSchema['empresa_nombre'] = new sfValidatorString(array(), array('required' => "Campo Obligatorio."));
      $this->validatorSchema['empresa_rut'] = new sfValidatorRut(array('required' => false));
      $this->validatorSchema['contabilidad_email'] = new sfValidatorEmail(array('required' => false), array('invalid' => "Debe ser un Email válido"));
      $this->validatorSchema['ventas_email'] = new sfValidatorEmail(array('required' => false), array('invalid' => "Debe ser un Email válido"));
      
  }
}
