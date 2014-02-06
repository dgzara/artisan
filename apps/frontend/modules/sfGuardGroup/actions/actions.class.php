<?php

require_once dirname(__FILE__) . '/../lib/sfGuardGroupGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/sfGuardGroupGeneratorHelper.class.php';

/**
 * sfGuardGroup actions.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardGroup
 * @author     Fabien Potencier
 * @version    SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardGroupActions extends autoSfGuardGroupActions {

    public function executeIndex(sfWebRequest $request) {
        
        if ($request->isXmlHttpRequest())
        {
            $q = Doctrine_Query::create()
            ->from('SfGuardGroup');
            
            $pager = new sfDoctrinePager('SfGuardGroup', $request->getParameter('iDisplayLength'));
            $pager->setQuery($q);
            $pager->setPage($request->getParameter('page', 1));
            $pager->init();
            
            $aaData = array();
            $list = $pager->getResults();
            foreach ($list as $v)
            {
                $ver = $this->getController()->genUrl('sfGuardGroup/show?id='.$v->getId());
                $mod = $this->getController()->genUrl('sfGuardGroup/edit?id='.$v->getId());
                $del = $this->getController()->genUrl('sfGuardGroup/delete?id='.$v->getId());
                
                $aaData[] = array(
                                  "0" => $v->getId(),
                                  "1" => $v->getName(),
                                  "2" => $v->getDescription(),
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
    
    public function executeShow(sfWebRequest $request){
        $this->group = Doctrine_Core::getTable('SfGuardGroup')->find(array($request->getParameter('id')));
        $this->forward404Unless($this->group);
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new sfGuardGroupForm();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->form = new sfGuardGroupForm();
        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($sf_guard_group = Doctrine::getTable('sfGuardGroup')->findOneById($request->getParameter('id')), sprintf('Object sf_guard_group does not exist (%s).', $request->getParameter('id')));
        $this->form = new sfGuardGroupForm($sf_guard_group);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($sf_guard_group = Doctrine::getTable('sfGuardGroup')->find(array($request->getParameter('id'))), sprintf('Object sf_guard_group does not exist (%s).', $request->getParameter('id')));
        $this->form = new sfGuardGroupForm($sf_guard_group);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();
        $this->forward404Unless($sf_guard_group = Doctrine::getTable('sfGuardGroup')->find(array($request->getParameter('id'))), sprintf('Object sf_guard_group does not exist (%s).', $request->getParameter('id')));

        $sf_guard_group->delete();

        $this->redirect('guard/groups');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $cliente = $form->save();
            $this->redirect('guard/groups');
        }
    }

}
