<?php

/**
 * captura actions.
 *
 * @package    quesos
 * @subpackage captura
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class capturaActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $formato = new sfNumberFormat('es_CL');
    
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('Captura');

      $pager = new sfDoctrinePager('Captura', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('captura/show?id='.$v->getId());

        if($v->getModo() == 0){
           $modo = 'Normal';
        }
        else{
           $modo = 'Rápido';
        }

	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $modo,
          "2" => $v->getDateTimeObject('fecha')->format('d-m-Y'),
          "3" => $v->getProducto()->getNombre()." ".$v->getProducto()->getPresentacion()." ".$v->getProducto()->getUnidad(),
          "4" => $v->getLocal()->getNombreCompleto(),
          "5" => '$'.$formato->format($v->getPrecio(),'d', 'CLP'),
          "6" => $v->getStock(),
          "7" => '<a href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0"></a>'
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

  public function executeEstadisticas(sfWebRequest $request){

    
    if($request->getParameter('desde') != NULL){
        $fechaDesde = $request->getParameter('desde');
        $desde = explode("-", $fechaDesde);
        $antes = $desde[2]."-".$desde[1]."-".$desde[0];
        $hayDesde = true;
        }
    else{
       $desde = "0000-00-00";
    }

    if($request->getParameter('hasta') != NULL){
        $fechaHasta = $request->getParameter('hasta');
        $hasta = explode("-", $fechaHasta);
        $despues = $hasta[2]."-".$hasta[1]."-".$hasta[0];
        $hayDesde = true;
        }
    else{
       $hasta = "2100-10-10";
    }    

    if($hayDesde == true){
        
        $locales = Doctrine_Core::getTable("Local")
                ->createQuery('a')
          ->execute();

        $nombresProductosLocal = array();
        $numLocal = 0;
        foreach($locales as $local):
        $capturas = $local->getCapturas();
        $numCapturas = 0;
            foreach($capturas as $captura):
                $nums = explode(" ", $captura->getFecha());
                $fechaCaptura = $nums[0];
                if(strtotime($fechaCaptura)> strtotime($antes) && strtotime($fechaCaptura) < strtotime($despues)){
                    printf(strtotime($antes)." ".strtotime($fechaCaptura)." ". strtotime($despues).";");
                    $nombresProductosLocal[$numLocal][$numCapturas] = $captura->getProducto()->getNombre()." ".$captura->getProducto()->getPresentacion()." ".$captura->getProducto()->getUnidad().";".$captura->getStock().";".$captura->getMermas().";".$captura->getFueraFormato().";".$captura->getFecha();
                    $numCapturas++;
                }
            endforeach;
        $numLocal++;
        endforeach;

        $this->cosas = $nombresProductosLocal;
        $this->hayCosas = true;
        $this->locales = Doctrine_Core::getTable("Local")
            ->createQuery('a')
        ->execute();
    }
    else{

    $this->locales = Doctrine_Core::getTable("Local")
            ->createQuery('a')
      ->execute();

   }


  }

   public function executeAlertas(sfWebRequest $request)
  {

       $filtrando = $request->getParameter('accion');

    if($request->getParameter('accion') != NULL){

        if($filtrando == "todas"){
            $this->capturas = Doctrine_Core::getTable('Captura')
            ->createQuery('a')
            ->from('Captura a, a.Producto c')
            ->orderBy('a.id DESC')
            ->where('a.stock <= c.stock_critico')
            ->execute();
            $this->modo = "Todas";
       }
       else if($filtrando == "revisadas"){
            $this->capturas = Doctrine_Core::getTable('Captura')
            ->createQuery('a')
            ->from('Captura a, a.Producto c')
            ->orderBy('a.id DESC')
            ->where('a.stock <= c.stock_critico')
            ->andWhere('a.alertado = true')
            ->execute();
            $this->modo = "Revisadas";
       }
       else if($filtrando == "noRevisadas"){
            $this->capturas = Doctrine_Core::getTable('Captura')
            ->createQuery('a')
            ->from('Captura a, a.Producto c')
            ->orderBy('a.id DESC')
            ->where('a.stock <= c.stock_critico')
            ->andWhere('a.alertado = false')
            ->execute();
            $this->modo = "No Revisadas";
       }
    }

    else{
     $this->capturas = Doctrine_Core::getTable('Captura')
            ->createQuery('a')
            ->from('Captura a, a.Producto c')
            ->orderBy('a.id DESC')
            ->where('a.stock <= c.stock_critico')
            ->andWhere('a.alertado = false')
            ->execute();
     $this->modo = "No Revisadas";
    }

    

  }

  public function executeRevisado(sfWebRequest $request)
  {
    $id = $request->getParameter('accion');
    $captura = Doctrine_Core::getTable('Captura')->findOneById($id);
    $captura->setAlertado(1);
    $captura->save();
    $this->redirect('captura/alertas');

  }

  public function executeExcel(sfWebRequest $request){

    $this->capturas = Doctrine_Core::getTable('Captura')
      ->createQuery('a')
      ->orderBy('a.id DESC')
      ->execute();

    $this->calidades = Doctrine_Core::getTable('AspectoCalidad')
      ->createQuery('a')
      ->orderBy('a.id DESC')
      ->execute();

    $capturas = $this->capturas;
    $calidades = $this->calidades;

    

    $header = "Modo\tFecha\tLocal\tProducto\tPrecio\tStock\tFacing\tFuera de Formato\tMermas\tPromocion\tPromotoras";
    foreach ($calidades as $calidad){
        $header .= "\t".$calidad->getDescripcion();
    }
    $header .= "\tMarca\tNombre\tPresentacion\tUnidad\tPrecio Captura\n";

    foreach($capturas as $captura) {
        $competencias = $captura->getProductoCompetenciaCaptura();
        if(count($competencias) != 0){
            foreach($competencias as $competencia){

                $valor = $captura->getModo();
                if($valor == 1)
                    $modo = "Rapido";
                else
                    $modo = "Normal";
                $datos .= $modo."\t";
                $datos .= $captura->getFecha()."\t";
                $datos .= $captura->getLocal()->getCliente()->getName().' '.$captura->getLocal()->getNombre()."\t";
                $datos .= $captura->getProducto()->getNombre()."\t";
                $datos .= $captura->getPrecio()."\t";
                $datos .= $captura->getStock()."\t";
                $datos .= $captura->getFacing()."\t";
                $datos .= $captura->getFueraFormato()."\t";
                $datos .= $captura->getMermas()."\t";
                $datos .= $captura->getPromocion()."\t";
                $datos .= $captura->getPromotoras()."\t";

                $aspectos = $captura->getAspectoCalidadCaptura();
                foreach ($aspectos as $aspecto){
                    $valor = $aspecto->getValor();
                    if($valor == 1)
                        $value =  "verdadero";
                    else
                        $value =  "falso";
                    $datos .= $value."\t";
                }

                $datos .= $competencia->getProductoCompetencia()->getMarca()->getNombre()."\t";
                $datos .= $competencia->getProductoCompetencia()->getNombre()."\t";
                $datos .= $competencia->getProductoCompetencia()->getPresentacion()."\t";
                $datos .= $competencia->getProductoCompetencia()->getUnidad()."\t";
                $datos .= $competencia->getPrecioCaptura()."\t";
                $datos .= "\n"; //fin de linea
            }
        }
        else{

                $valor = $captura->getModo();
                if($valor == 1)
                    $modo = "Rapido";
                else
                    $modo = "Normal";
                $datos .= $modo."\t";
                $datos .= $captura->getFecha()."\t";
                $datos .= $captura->getLocal()->getCliente()->getName().' '.$captura->getLocal()->getNombre()."\t";
                $datos .= $captura->getProducto()->getNombre()."\t";
                $datos .= $captura->getPrecio()."\t";
                $datos .= $captura->getStock()."\t";
                $datos .= $captura->getFacing()."\t";
                $datos .= $captura->getFueraFormato()."\t";
                $datos .= $captura->getMermas()."\t";
                $datos .= $captura->getPromocion()."\t";
                $datos .= $captura->getPromotoras()."\t";

                $aspectos = $captura->getAspectoCalidadCaptura();
                foreach ($aspectos as $aspecto){
                    $valor = $aspecto->getValor();
                    if($valor == 1)
                        $value =  "verdadero";
                    else
                        $value =  "falso";
                    $datos .= $value."\t";
                }
                $datos .= "\n"; //fin de linea

        }
    }


    $datos = str_replace('Ã¡', 'á', $datos);
    $datos = str_replace('Ã©', 'é', $datos);
    $datos = str_replace('Ã*', 'í', $datos);
    $datos = str_replace('Ã³', 'ó', $datos);
    $datos = str_replace('ú', '&uacute;', $datos);
    $datos = str_replace('Ú', '&uacute', $datos);


    header("Content-type: application/vnd.ms-excel; charset=UTF-32");
    header("Content-Disposition: attachment; filename=Capturas-".date("Y-m-d-H-i-s").".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    $header = utf8_decode($header);
    $datos = utf8_decode($datos);
    print $header."\n".$datos;
    return sfView::NONE;
  }

 


  public function executeShow(sfWebRequest $request)
  {
    $this->captura = Doctrine_Core::getTable('Captura')->find(array($request->getParameter('id')));
    $this->formato = new sfNumberFormat('es_CL');
    $this->forward404Unless($this->captura);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new CapturaForm();
    $this->form_calidad = new AspectoCalidadForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new CapturaForm();
    $this->form_calidad = new AspectoCalidadForm();

    $this->processForm2($request, $this->form, $this->form_calidad);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($captura = Doctrine_Core::getTable('Captura')->find(array($request->getParameter('id'))), sprintf('Object captura does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($aspecto = Doctrine_Core::getTable('AspectoCalidad')->findByCapturaId(array($request->getParameter('id'))), sprintf('Object AspectoCalidad does not exist (%s).', $request->getParameter('id')));
    $this->form = new CapturaForm($captura);
    $this->form_calidad = new AspectoCalidadForm($aspecto[0]);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($captura = Doctrine_Core::getTable('Captura')->find(array($request->getParameter('id'))), sprintf('Object captura does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($aspecto = Doctrine_Core::getTable('AspectoCalidad')->findByCapturaId(array($request->getParameter('id'))), sprintf('Object AspectoCalidad does not exist (%s).', $request->getParameter('id')));

    $this->form = new CapturaForm($captura);
    $this->form_calidad = new AspectoCalidadForm($aspecto[0]);

    $this->processForm($request, $this->form, $this->form_calidad);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($captura = Doctrine_Core::getTable('Captura')->find(array($request->getParameter('id'))), sprintf('Object captura does not exist (%s).', $request->getParameter('id')));
    $captura->delete();

    $this->redirect('captura/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form, sfForm $form2)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    $form2->bind($request->getParameter($form2->getName()), $request->getFiles($form2->getName()));

    if ($form->isValid() && $form2->isValid())
    {
      $captura = $form->save();
      $form2->save();

      $this->redirect('captura/edit?id='.$captura->getId());
    }
  }

  protected function processForm2(sfWebRequest $request, sfForm $form, sfForm $form2)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    $form2->bind($request->getParameter($form2->getName()), $request->getFiles($form2->getName()));

    if ($form->isValid() && $form2->isValid())
    {
      $captura = $form->save();
      $f2 = $form2->updateObject();
      $f2->setCapturaId($captura->getId());
      $f2->save();
      $this->redirect('captura/edit?id='.$captura->getId());
    }
  }

  public function executeCapturas(sfWebRequest $request)
  {
        $moduleName = "Captura";
        $helps = array(
            "captura/index" =>  array("Lista" => ' Permite ver un listado de las capturas que se han ingresado a la base de datos.'),
            "captura/alertas" => array("Alertas" => 'Permite ver un listado de los productos que están bajo el stock mínimo en las góndolas.')
        //Uno de estos por cada vista que hay en el modulo
        );

        echo get_partial("../ayuda", array("helps" => $helps, "moduleName" => $moduleName));
        return true;

  }

  public function executeFiltrar(sfWebRequest $request)
  {
        $moduleName = "Captura";
        $helps = array(
            "captura/index" =>  array("Lista" => ' Permite ver un listado de las capturas que se han ingresado a la base de datos.'),
            "captura/alertas" => array("Alertas" => 'Permite ver un listado de los productos que están bajo el stock mínimo en las góndolas.')
        //Uno de estos por cada vista que hay en el modulo
        );

        echo get_partial("../ayuda", array("helps" => $helps, "moduleName" => $moduleName));
        return true;

  }

}
