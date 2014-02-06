<?php

/**
 * aspectos_calidad actions.
 *
 * @package    quesos
 * @subpackage aspectos_calidad
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class aspectos_calidadActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('AspectoCalidad');

      $pager = new sfDoctrinePager('AspectoCalidad', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('aspectos_calidad/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('aspectos_calidad/edit?id='.$v->getId());

	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getNombre(),
          "2" => $v->getDescripcion(),
          "3" => '<a href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "4" => '<a href="'.$mod.'"><img src="images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->aspecto_calidad = Doctrine_Core::getTable('AspectoCalidad')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->aspecto_calidad);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new AspectoCalidadForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new AspectoCalidadForm();

    $this->processForm($request, $this->form);

    //$this->setTemplate('new');
    $this->redirect('aspectos_calidad/index');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($aspecto_calidad = Doctrine_Core::getTable('AspectoCalidad')->find(array($request->getParameter('id'))), sprintf('Object aspecto_calidad does not exist (%s).', $request->getParameter('id')));
    $this->form = new AspectoCalidadForm($aspecto_calidad);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($aspecto_calidad = Doctrine_Core::getTable('AspectoCalidad')->find(array($request->getParameter('id'))), sprintf('Object aspecto_calidad does not exist (%s).', $request->getParameter('id')));
    $this->form = new AspectoCalidadForm($aspecto_calidad);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($aspecto_calidad = Doctrine_Core::getTable('AspectoCalidad')->find(array($request->getParameter('id'))), sprintf('Object aspecto_calidad does not exist (%s).', $request->getParameter('id')));
    $aspecto_calidad->delete();

    $this->redirect('aspectos_calidad/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $aspecto_calidad = $form->save();

      $this->redirect('aspectos_calidad/edit?id='.$aspecto_calidad->getId());
    }
  }

  public function executeCompetencias(sfWebRequest $request)
  {
        $moduleName = "Productos de la Competencia";
        $helps = array("aspectos_calidad/index" =>  array("Lista de Aspectos de Calidad" => ' Permite ver un listado de los aspectos de calidad a ser monitoreados mediante la aplicación móvil.'),
                        "aspectos_calidad/new" => array("Agregar Aspecto de Calidad" => 'Permite agregar un aspecto de calidad a la base de datos, para que se despliegue a la hora de realizar capturas en la aplicación móvil.')
		//Uno de estos por cada vista que hay en el modulo
        );

        echo get_partial("../ayuda", array("helps" => $helps, "moduleName" => $moduleName));
        return true;
  }
}
