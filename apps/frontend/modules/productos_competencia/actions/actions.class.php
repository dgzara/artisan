<?php

/**
 * productos_competencia actions.
 *
 * @package    quesos
 * @subpackage productos_competencia
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class productos_competenciaActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('ProductoCompetencia');

      $pager = new sfDoctrinePager('ProductoCompetencia', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('productos_competencia/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('productos_competencia/edit?id='.$v->getId());
        
	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getMarca()->getNombre(),
          "2" => $v->getNombre(),
          "3" => $v->getPresentacion(),
          "4" => $v->getUnidad(),
          "5" => '<a href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "6" => '<a href="'.$mod.'"><img src="images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
        );
      }

      $output = array(
     	"iTotalRecords" => count($pager),
     	"iTotalDisplayRecords" => $request->getParameter('iDisplayLength'),
    	"aaData" => $aaData,
      );

      return $this->renderText(json_encode($output));
   }
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->producto_competencia = Doctrine_Core::getTable('ProductoCompetencia')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->producto_competencia);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ProductoCompetenciaForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ProductoCompetenciaForm();

    $this->processForm($request, $this->form);

    //$this->setTemplate('new');
    $this->redirect('productos_competencia/index');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($producto_competencia = Doctrine_Core::getTable('ProductoCompetencia')->find(array($request->getParameter('id'))), sprintf('Object producto_competencia does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProductoCompetenciaForm($producto_competencia);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($producto_competencia = Doctrine_Core::getTable('ProductoCompetencia')->find(array($request->getParameter('id'))), sprintf('Object producto_competencia does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProductoCompetenciaForm($producto_competencia);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($producto_competencia = Doctrine_Core::getTable('ProductoCompetencia')->find(array($request->getParameter('id'))), sprintf('Object producto_competencia does not exist (%s).', $request->getParameter('id')));
    $producto_competencia->delete();

    $this->redirect('productos_competencia/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $producto_competencia = $form->save();

      $this->redirect('productos_competencia/edit?id='.$producto_competencia->getId());
    }
  }

  public function executeCompetencias(sfWebRequest $request)
  {
        $moduleName = "Productos de la Competencia";
        $helps = array("marca/index" =>  array("Lista de Marcas" => ' Permite ver un listado de las marcas de los productos de la competencia.'),
                        "marca/new" => array("Agregar Marca" => 'Permite agregar una marca a la base de datos, para luego poder asociarla a un producto de la competencia.'),
                        "productos_competencia/index" =>  array("Lista de Competencias" => ' Permite ver un listado de los productos de la competencia ingresados en la base de datos.'),
                        "productos_competencia/new" => array("Agregar Competencia" => 'Permite agregar un nuevo producto de la competencia.'),
                        "producto_competencia_producto/index" =>  array("Lista de Asociaciones" => ' Permite ver un listado de las asociaciones que existen entre productos ARTISAN y productos de la competencia.'),
                        "producto_competencia_producto/new" => array("Agregar Asociación" => 'Permite agregar una asociación entre un producto ARTISAN y un producto de la competencia, de modo que ésta se refleje en la aplicación móvil.')
		//Uno de estos por cada vista que hay en el modulo
        );

        echo get_partial("../ayuda", array("helps" => $helps, "moduleName" => $moduleName));
        return true;
  }
}
