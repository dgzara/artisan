<?php

/**
 * planproduccion actions.
 *
 * @package    quesos
 * @subpackage planproduccion
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class planproduccionActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
           ->from('PlanProduccion');

      $pager = new sfDoctrinePager('PlanProduccion', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();

      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('planproduccion/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('planproduccion/edit?id='.$v->getId());

        $productos = $v->getProductos();
        $list_productos = '<ul>';

        foreach($productos as $producto){
          $list_productos = $list_productos.'<li><a href="'.$ver. '" target="_blank">' . $producto->getNombreCompleto(). '</a> (' . $producto->getCantidad() . ')</li>';
        }
        $list_productos = $list_productos.'</ul>';

        $aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getDateTimeObject('fecha')->format('d-m-Y'),
          "2" => $v->getComentarios(),
          "3" => $list_productos,
          "4" => '<a class="jt" rel="/web/planproduccion/preview/'.$v->getId().'" title="Plan de Producción '.$v->getId().'" href="'.$ver.'"><img src="images/tools/icons/event_icons/ico-story.png" border="0" /></a>',
          "5" => '<a href="'.$mod.'"><img src="images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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

  public function dateadd($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0) {
        $date_r = getdate(strtotime($date));
        $date_result = date("Y/m/d", mktime(($date_r["hours"] + $hh), ($date_r["minutes"] + $mn), ($date_r["seconds"] + $ss), ($date_r["mon"] + $mm), ($date_r["mday"] + $dd), ($date_r["year"] + $yy)));
        return $date_result;
    }

  public function executeShow(sfWebRequest $request)
  {
    $this->plan_produccion = Doctrine_Core::getTable('PlanProduccion')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->plan_produccion);
  }
  
    public function executePreview(sfWebRequest $request)
  {
    $this->plan_produccion = Doctrine_Core::getTable('PlanProduccion')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->plan_produccion);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PlanProduccionForm();
    $this->ramas = Doctrine_Core::getTable('Rama')->findAll();

    $this->productos = array();
    $this->plan_produccion_productos = array();
    $this->fecha_elaboraciones = array();

    foreach($this->ramas as $rama){
        $this->productos[$rama->getId()] = Doctrine_Core::getTable('Producto')->findByRamaId($rama->getId());
        $this->plan_produccion_productos[$rama->getId()] = array();

        foreach($this->productos[$rama->getId()] as $producto){

            $this->fecha_elaboraciones[$rama->getId()] = $producto->getMaduracion();
            
            $this->plan_produccion_productos[$rama->getId()][$producto->getId()] = new PlanProduccionProductoForm();
            $this->widgetSchema = $this->plan_produccion_productos[$rama->getId()][$producto->getId()]->getWidgetSchema();
            $this->validatorSchema = $this->plan_produccion_productos[$rama->getId()][$producto->getId()]->getValidatorSchema();
            $this->widgetSchema->setNameFormat('plan_produccion_producto_'.$producto->getId().'[%s]');
            $this->widgetSchema['producto_id']->setAttribute('value', $producto->getId());
        }
    }
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->form = new PlanProduccionForm();
    $this->ramas = Doctrine_Core::getTable('Rama')->findAll();
    $this->productos = array();
    $this->plan_produccion_productos = array();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($plan_produccion = Doctrine_Core::getTable('PlanProduccion')->find(array($request->getParameter('id'))), sprintf('Object plan_produccion does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($plan_produccion_productos = Doctrine_Core::getTable('PlanProduccionProducto')->findByPlanId($plan_produccion->getId()));

    $this->form = new PlanProduccionForm($plan_produccion);
    $this->ramas = Doctrine_Core::getTable('Rama')->findAll();

    $this->productos = array();
    $this->plan_produccion_productos = array();
    $this->fecha_elaboraciones = array();

    foreach($this->ramas as $rama){
        $this->productos[$rama->getId()] = Doctrine_Core::getTable('Producto')->findByRamaId($rama->getId());
        $this->plan_produccion_productos[$rama->getId()] = array();

        foreach($this->productos[$rama->getId()] as $producto){

            $this->fecha_elaboraciones[$rama->getId()] = $producto->getMaduracion();

            $plan_produccion_producto = Doctrine_Core::getTable('PlanProduccionProducto')->findByDql('producto_id = ? AND plan_id = ?', array($producto->getId(), $plan_produccion->getId()))->getFirst();

            $this->plan_produccion_productos[$rama->getId()][$producto->getId()] = new PlanProduccionProductoForm($plan_produccion_producto);
            $this->widgetSchema = $this->plan_produccion_productos[$rama->getId()][$producto->getId()]->getWidgetSchema();
            $this->validatorSchema = $this->plan_produccion_productos[$rama->getId()][$producto->getId()]->getValidatorSchema();
            $this->widgetSchema->setNameFormat('plan_produccion_producto_'.$producto->getId().'[%s]');
            $this->widgetSchema['producto_id']->setAttribute('value', $producto->getId());
        }
    }
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($plan_produccion = Doctrine_Core::getTable('PlanProduccion')->find(array($request->getParameter('id'))), sprintf('Object plan_produccion does not exist (%s).', $request->getParameter('id')));
    
    $this->form = new PlanProduccionForm($plan_produccion);
    $this->ramas = Doctrine_Core::getTable('Rama')->findAll();
    $this->productos = array();
    $this->plan_produccion_productos = array();

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($plan_produccion = Doctrine_Core::getTable('PlanProduccion')->find(array($request->getParameter('id'))), sprintf('Object plan_produccion does not exist (%s).', $request->getParameter('id')));
    $plan_produccion->delete();

    $this->redirect('planproduccion/index');
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
        $plan_produccion = $form->save();

        foreach($this->ramas as $rama){
            $this->productos[$rama->getId()] = Doctrine_Core::getTable('Producto')->findByRamaId($rama->getId());
            $this->plan_produccion_productos[$rama->getId()] = array();

            foreach($this->productos[$rama->getId()] as $producto){
                $this->fecha_elaboraciones[$rama->getId()] = $producto->getMaduracion();

                $plan_produccion_producto = Doctrine_Core::getTable('PlanProduccionProducto')->findByDql('producto_id = ? AND plan_id = ?', array($producto->getId(), $plan_produccion->getId()))->getFirst();

                $this->plan_produccion_productos[$rama->getId()][$producto->getId()] = new PlanProduccionProductoForm($plan_produccion_producto);
                $this->widgetSchema = $this->plan_produccion_productos[$rama->getId()][$producto->getId()]->getWidgetSchema();
                $this->validatorSchema = $this->plan_produccion_productos[$rama->getId()][$producto->getId()]->getValidatorSchema();
                $this->widgetSchema->setNameFormat('plan_produccion_producto_'.$producto->getId().'[%s]');
                $this->widgetSchema['producto_id']->setAttribute('value', $producto->getId());

                $this->processFormProducto($request, $this->plan_produccion_productos[$rama->getId()][$producto->getId()], $plan_produccion);
            }
        }
        $this->redirect('planproduccion/index');

    }
    else {
        echo 'fail';
    }
  }

  protected function processFormProducto(sfWebRequest $request, sfForm $form, $plan_produccion)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if($form->isValid()){
        $p = $form->updateObject();
        $p->setPlanId($plan_produccion->getId());
        if($p->getCantidad() != 0){
            $p->save();
        }
    }
    else{
        echo 'fail';
    }
  }

  public function executePdf(sfWebRequest $request)
    {
      $config = sfTCPDFPluginConfigHandler::loadConfig();
      //$config = sfTCPDFPluginConfigHandler::loadConfig('my_config');
      sfTCPDFPluginConfigHandler::includeLangFile($this->getUser()->getCulture());

      $doc_title    = "Plan Producción";
      $doc_subject  = "Detalles del plan de producción";
      $doc_keywords = "Artisan";
      //$htmlcontent  = "<br /><h1>heading 1</h1><h2>heading 2</h2><h3>heading 3</h3><h4>heading 4</h4><h5>heading 5</h5><h6>heading 6</h6>ordered list:<br /><ol><li><b>bold text</b></li><li><i>italic text</i></li><li><u>underlined text</u></li><li><a href="http://www.tecnick.com">link to http://www.tecnick.com</a></li><li>test break<br />second line<br />third line</li><li><font size="+3">font + 3</font></li><li><small>small text</small></li><li>normal <sub>subscript</sub> <sup>superscript</sup></li></ul><hr />table:<br /><table border="1" cellspacing="1" cellpadding="1"><tr><th>#</th><th>A</th><th>B</th></tr><tr><th>1</th><td bgcolor="#cccccc">A1</td><td>B1</td></tr><tr><th>2</th><td>A2</td><td>B2</td></tr><tr><th>3</th><td>A3</td><td><font color="#FF0000">B3</font></td></tr></table><hr />image:<br /><img src="sfTCPDFPlugin/images/logo_example.png" alt="test alt attribute" width="100" height="100" border="0" />";

      //create new PDF document (document units are set by default to millimeters)
      $pdf = new sfTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

      // set document information
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor(PDF_AUTHOR);
      $pdf->SetTitle($doc_title);
      $pdf->SetSubject($doc_subject);
      $pdf->SetKeywords($doc_keywords);

      $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, ALGO);

      //set margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

      //set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

      //initialize document
      $pdf->AliasNbPages();
      $pdf->AddPage();

      // set barcode
      //$pdf->SetBarcode("FIRMA");

      $plan_produccion_id = $request->getParameter('plan_produccion_id');
      $plan_produccion = Doctrine_Core::getTable('PlanProduccion')->find(array($request->getParameter('plan_produccion_id')));

      sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');


      $htmlcontent  = get_partial('planproduccion/pdf',array('plan_produccion'=>$plan_produccion));
      $pdf->writeHTML($htmlcontent , true, 0);

      // output two html columns
      //$first_column_width = 80;
      //$current_y_position = $pdf->getY();
      //$pdf->writeHTMLCell($first_column_width, 0, 0, $current_y_position, "<b>hello</b>", 0, 0, 0);
      //$pdf->writeHTMLCell(0, 0, $first_column_width, $current_y_position, "<i>world</i>", 0, 1, 0);

      // output some content
      //$pdf->Cell(0,10,"TEST Bold-Italic Cell",1,1,'C');

      // output some UTF-8 test content
      //$pdf->AddPage();
      //$pdf->SetFont("FreeSerif", "", 12);

      //$utf8text = file_get_contents(K_PATH_CACHE. "utf8test.txt", false); // get utf-8 text form file
      //$pdf->SetFillColor(230, 240, 255, true);
      //$pdf->Write(5,$utf8text, '', 1);

   //   Two HTML columns test
   //   $pdf->AddPage();
   //   $right_column = "<b>right column</b> right column right column right column right column
   //   right column right column right column right column right column right column
   //   right column right column right column right column right column right column";
   //   $left_column = "<b>left column</b> left column left column left column left column left
   //   column left column left column left column left column left column left column
   //   left column left column left column left column left column left column left
   //   column";
   //   $first_column_width = 80;
   //   $second_column_width = 80;
   //   $column_space = 20;
   //   $current_y_position = $pdf->getY();
   //   $pdf->writeHTMLCell($first_column_width, 0, 0, 0, $left_column, 1, 0, 0);
   //   $pdf->Cell(0);
   //   $pdf->writeHTMLCell($second_column_width, 0, $first_column_width+$column_space, $current_y_position, $right_column, 0, 0, 0);

      // add page header/footer
      $pdf->setPrintHeader(true);
      $pdf->setPrintFooter(true);

      // Multicell test
      /*$pdf->MultiCell(40, 5, "A test multicell line 1\ntest multicell line 2\ntest multicell line 3", 1, 'J', 0, 0);
      $pdf->MultiCell(40, 5, "B test multicell line 1\ntest multicell line 2\ntest multicell line 3", 1, 'J', 0);
      $pdf->MultiCell(40, 5, "C test multicell line 1\ntest multicell line 2\ntest multicell line 3", 1, 'J', 0, 0);
      $pdf->MultiCell(40, 5, "D test multicell line 1\ntest multicell line 2\ntest multicell line 3", 1, 'J', 0, 2);
      $pdf->MultiCell(40, 5, "F test multicell line 1\ntest multicell line 2\ntest multicell line 3", 1, 'J', 0);
*/
      // Close and output PDF document
      $pdf->Output();

      // Stop symfony process
      throw new sfStopException();
    }
}
