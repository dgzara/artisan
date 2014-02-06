<?php

/**
 * plantilla_columnas actions.
 *
 * @package    quesos
 * @subpackage plantilla_columnas
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class plantilla_columnasActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('PlantillaColumna');

      $pager = new sfDoctrinePager('PlantillaColumna', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('plantilla_columnas/show?id='.$v);
        $mod = $this->getController()->genUrl('plantilla_columnas/edit?id='.$v);
        $del = $this->getController()->genUrl('plantilla_columnas/delete?id='.$v);
        
	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getNombre(),
          "2" => '<a href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "3" => '<a href="'.$mod.'"><img src="images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->plantilla_columna = Doctrine_Core::getTable('PlantillaColumna')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->plantilla_columna);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PlantillaColumnaForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PlantillaColumnaForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($plantilla_columna = Doctrine_Core::getTable('PlantillaColumna')->find(array($request->getParameter('id'))), sprintf('Object plantilla_columna does not exist (%s).', $request->getParameter('id')));
    $this->form = new PlantillaColumnaForm($plantilla_columna);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($plantilla_columna = Doctrine_Core::getTable('PlantillaColumna')->find(array($request->getParameter('id'))), sprintf('Object plantilla_columna does not exist (%s).', $request->getParameter('id')));
    $this->form = new PlantillaColumnaForm($plantilla_columna);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($plantilla_columna = Doctrine_Core::getTable('PlantillaColumna')->find(array($request->getParameter('id'))), sprintf('Object plantilla_columna does not exist (%s).', $request->getParameter('id')));
    $plantilla_columna->delete();

    $this->redirect('plantilla_columnas/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $plantilla_columna = $form->save();

      $this->redirect('plantilla_columnas/index');
    }
  }
}
