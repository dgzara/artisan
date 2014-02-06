<?php

require_once dirname(__FILE__) . '/../lib/sfGuardUserGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/sfGuardUserGeneratorHelper.class.php';

/**
 * sfGuardUser actions.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardUser
 * @author     Fabien Potencier
 * @version    SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardUserActions extends autoSfGuardUserActions {

    public function executeIndex(sfWebRequest $request) {

        if ($request->isXmlHttpRequest())
        {
          $q = Doctrine_Query::create()
          ->from('SfGuardUser');

          $pager = new sfDoctrinePager('SfGuardUser', $request->getParameter('iDisplayLength'));
          $pager->setQuery($q);
          $pager->setPage($request->getParameter('page', 1));
          $pager->init();

          $aaData = array();
          $list = $pager->getResults();
          foreach ($list as $v)
          {
            $ver = $this->getController()->genUrl('sfGuardUser/show?id='.$v->getId());
            $mod = $this->getController()->genUrl('sfGuardUser/edit?id='.$v->getId());
            $del = $this->getController()->genUrl('sfGuardUser/delete?id='.$v->getId());

            $aaData[] = array(
              "0" => $v->getId(),
              "1" => $v->getFirstName(),
              "2" => $v->getLastName(),
              "3" => $v->getEmailAddress(),
              "4" => $v->getDateTimeObject('last_login')->format('d-m-Y'),
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

    public function executeShow(sfWebRequest $request){
        $this->usuario = Doctrine_Core::getTable('SfGuardUser')->find(array($request->getParameter('id')));
        $this->forward404Unless($this->usuario);
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new sfGuardUserForm();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->form = new sfGuardUserForm();
        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {        
        
        $this->forward404Unless($sf_guard_user = Doctrine::getTable('sfGuardUser')->findOneById($request->getParameter('id')), sprintf('Object sf_guard_user does not exist (%s).', $request->getParameter('id')));
        $this->form = new sfGuardUserForm($sf_guard_user);

        $this->widgetSchema = $this->form->getWidgetSchema();
        $this->widgetSchema->setLabels(array(
            'password' => 'Contraseña (Si desea cambiarla):',
            'password_confirmation' => 'Confirmación Contraseña (Si desea cambiarla):'
        ));
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($sf_guard_user = Doctrine::getTable('sfGuardUser')->find(array($request->getParameter('id'))), sprintf('Object sf_guard_user does not exist (%s).', $request->getParameter('id')));
        $this->form = new sfGuardUserForm($sf_guard_user);

        $this->validatorSchema=$this->form->getValidatorSchema();
        $this->validatorSchema['password']->setOption('required', false);
        $this->validatorSchema['password_confirmation']->setOption('required', false);
        

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();
        $this->forward404Unless($sf_guard_user = Doctrine::getTable('sfGuardUser')->find(array($request->getParameter('id'))), sprintf('Object sf_guard_user does not exist (%s).', $request->getParameter('id')));

        $sf_guard_user->delete();

        $this->redirect('guard/users');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $cliente = $form->save();
            $this->redirect('guard/users');
        }
    }

}
