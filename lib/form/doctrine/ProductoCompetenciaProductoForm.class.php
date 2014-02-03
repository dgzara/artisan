<?php

/**
 * ProductoCompetenciaProducto form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProductoCompetenciaProductoForm extends BaseProductoCompetenciaProductoForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at']);

      $this->widgetSchema['producto_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Productos --'=>Doctrine_Core::getTable('Producto')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));
      $this->widgetSchema['producto_competencia_id'] = new sfWidgetFormChoice(array(
                    'choices' => array('-- Seleccione --','-- Productos Competencia --'=>Doctrine_Core::getTable('ProductoCompetencia')->getNombresInput()),
                    'multiple' => false, 'expanded' => false
                ));
  }
}
