  <?php

/**
 * Registro actions.
 *
 * @package    quesos
 * @subpackage Registro
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RegistroActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->Registros = Doctrine_Core::getTable('Registro')->findAll();
  }

  public function executeGet_data(sfWebRequest $request)
    {
      $formato = new sfNumberFormat('es_CL');
      
      if ($request->isXmlHttpRequest())
      {
        $q = Doctrine_Query::create()
             ->from('Registro')
             ->limit(1);

        $pager = new sfDoctrinePager('Registro', $request->getParameter('iDisplayLength'));
        $pager->setQuery($q);
        $pager->setPage($request->getParameter('page', 1));
        $pager->init();

        $aaData = array();
        $list = $pager->getResults();

        
        foreach ($list as $v)
        {
          $ver = $this->getController()->genUrl('registro/show?id='.$v->getId());
          $mod = $this->getController()->genUrl('registro/edit?id='.$v->getId());

          
          

          $aaData[] = array(
            "0" => $v->getAccion(),
            "1" => $v->getNombre(),
            "2" => $v->getBodegaNombre(),
            "3" => $v->getCantidad(),
            "4" => $v->getCantidadVieja(),
            "5" => $v->getCantidadNueva(),

            "6" => $v->getDateTimeObject('created_at')->format('d M Y H:i:s'),

            "7" => $v->getUsuarioNombre(),

            "8" => $v->getUsuarioNombre(),

            "9" => $v->getUsuarioNombre(),

            );

          $output = array(
          "iTotalRecords" => count($pager),
          "iTotalDisplayRecords" => $request->getParameter('iDisplayLength'),
          "aaData" => $aaData,
        );
}

          }
        return $this->renderText(json_encode($output));
       }

          
  public function executeShow(sfWebRequest $request)
  {
    $this->Registros = Doctrine_Core::getTable('Registro')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->Registro);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new RegistroForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new RegistroForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($Registro = Doctrine_Core::getTable('Registro')->find(array($request->getParameter('id'))), sprintf('Object Registro does not exist (%s).', $request->getParameter('id')));
    $this->form = new RegistroForm($Registro);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($Registro = Doctrine_Core::getTable('Registro')->find(array($request->getParameter('id'))), sprintf('Object Registro does not exist (%s).', $request->getParameter('id')));
    $this->form = new RegistroForm($Registro);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($Registro = Doctrine_Core::getTable('Registro')->find(array($request->getParameter('id'))), sprintf('Object Registro does not exist (%s).', $request->getParameter('id')));
    $Registro->delete();

    $this->redirect('Registro/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $Registro = $form->save();

      $this->redirect('Registro/edit?id='.$Registro->getId());
    }
  }
}
