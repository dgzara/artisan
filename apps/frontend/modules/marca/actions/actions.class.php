<?php

/**
 * marca actions.
 *
 * @package    quesos
 * @subpackage marca
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class marcaActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('Marca');

      $pager = new sfDoctrinePager('Marca', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('marca/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('marca/edit?id='.$v->getId());
        
	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getNombre(),
          "2" => $v->getRubro(),
          "3" => '<a href="'.$ver.'"><img src="../images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "4" => '<a href="'.$mod.'"><img src="../images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->marca = Doctrine_Core::getTable('Marca')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->marca);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new MarcaForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new MarcaForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($marca = Doctrine_Core::getTable('Marca')->find(array($request->getParameter('id'))), sprintf('Object marca does not exist (%s).', $request->getParameter('id')));
    $this->form = new MarcaForm($marca);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($marca = Doctrine_Core::getTable('Marca')->find(array($request->getParameter('id'))), sprintf('Object marca does not exist (%s).', $request->getParameter('id')));
    $this->form = new MarcaForm($marca);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($marca = Doctrine_Core::getTable('Marca')->find(array($request->getParameter('id'))), sprintf('Object marca does not exist (%s).', $request->getParameter('id')));
    $marca->delete();

    $this->redirect('marca/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $marca = $form->save();

      $this->redirect('marca/edit?id='.$marca->getId());
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
