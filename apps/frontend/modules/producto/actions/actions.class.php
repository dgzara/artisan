<?php

/**
 * producto actions.
 *
 * @package    quesos
 * @subpackage producto
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class productoActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('Producto p')
      ->leftJoin('p.Rama r');

      $pager = new sfDoctrinePager('Producto', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('producto/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('producto/edit?id='.$v->getId());
        $del = $this->getController()->genUrl('producto/delete?id='.$v->getId());

	$aaData[] = array(
          "0" => $v->getCodigo(),
          "1" => $v->getRama()->getNombre(),
          "2" => $v->getNombre(),
          "3" => $v->getPresentacion().' '.$v->getUnidad(),
          "4" => $v->getDuracion(),
          "5" => $v->getMaduracion(),
          "6" => $v->getStockCritico(),
          "7" => '<a href="'.$ver.'"><img src="/web/images/tools/icons/event_icons/ico-story.png" border="0"></a>',
          "8" => '<a href="'.$mod.'"><img src="/web/images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
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
    $this->producto = Doctrine_Core::getTable('Producto')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->producto);
  }


  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ProductoForm();
    $this->form_descriptores = array();
    $this->form_descriptores[0] = new DescriptorDeProductoForm();
    $this->widgetSchemaDescriptor = $this->form_descriptores[0]->getWidgetSchema();
    $this->widgetSchemaDescriptor->setNameFormat('descriptor_de_producto_0[%s]');
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ProductoForm();
    $this->form_descriptores = array();
    $this->descriptores = array();

    $this->processForm($request, $this->form);
    
    $this->setTemplate('new');

  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($producto = Doctrine_Core::getTable('Producto')->find(array($request->getParameter('id'))), sprintf('Object producto does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($descriptores = Doctrine_Core::getTable('DescriptorDeProducto')->findByProductoId($producto->getId()));
    
    $this->form = new ProductoForm($producto);
    $this->form_descriptores = array();

    for($i=0; $i < count($descriptores); $i++){
        $this->form_descriptores[$i] = new DescriptorDeProductoForm($descriptores[$i]);
        $this->widgetSchemaDescriptor = $this->form_descriptores[$i]->getWidgetSchema();
        $this->widgetSchemaDescriptor->setNameFormat('descriptor_de_producto_'.$i.'[%s]');
    }

  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($producto = Doctrine_Core::getTable('Producto')->find(array($request->getParameter('id'))), sprintf('Object producto does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($this->descriptores = Doctrine_Core::getTable('DescriptorDeProducto')->findByProductoId($producto->getId()));
    
    $this->form = new ProductoForm($producto);
    $this->form_descriptores = array();

    $this->processForm($request, $this->form);

    $this->redirect('producto/index');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($producto = Doctrine_Core::getTable('Producto')->find(array($request->getParameter('id'))), sprintf('Object producto does not exist (%s).', $request->getParameter('id')));
    $producto->delete();

    $this->redirect('producto/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $producto = $form->save();

      // Contamos los descriptores generados
      $keys = array_keys($request->getPostParameters());
      $numero_descriptores = 0;                                                            // Numero de descriptores
      $nombre_descriptores = array();

      foreach($keys as $key=>$val){
          if(substr_count($val, 'descriptor_de_producto')){
              $nombre_descriptores[$numero_descriptores] = $val;
              $numero_descriptores++;
          }
      }

      // Revisamos la cantidad de descriptores, si surge una nueva debemos crearla
      $m = count($this->descriptores);

      if($m < $numero_descriptores){
          for($i = $m; $i < $numero_descriptores; $i++){
              $this->descriptores[$i] = null;
          }
      }

      // Vemos cuál es el máximo
      $descriptores_max = max(array($numero_descriptores, $m));

      // Guardamos los descriptores
      $indice_descriptor = 0;

      for($i = 0; $i < $descriptores_max; $i++){

          $busco_descriptor = 'descriptor_de_producto_'.$i;
          
          if(in_array($busco_descriptor, $nombre_descriptores)){
            $this->form_descriptores[$indice_descriptor] = new DescriptorDeProductoForm($this->descriptores[$i]);
            $this->widgetSchemaDescriptor = $this->form_descriptores[$indice_descriptor]->getWidgetSchema();
            $this->widgetSchemaDescriptor->setNameFormat('descriptor_de_producto_'.$i.'[%s]');
            $this->processFormDescriptor($request, $this->form_descriptores[$indice_descriptor], $producto);
            $indice_descriptor++;
          }
          else{
            $this->descriptores[$i]->delete();
          }
      }

      $this->redirect('producto/index');
    }
//    else{
//        echo 'fail form';
//    }
  }

  protected function processFormDescriptor(sfWebRequest $request, sfForm $form, $producto)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $descriptor = $form->updateObject();
      $descriptor->setProductoId($producto->getId());
      if($descriptor->getCantidad() != ''){
          $descriptor->save();
      }
    }
//    else{
//        echo 'fail Descriptor';
//    }
  }

  public function executeCargarDescriptor(sfWebRequest $request){
    $i = $request->getParameter('i');

    $this->form_descriptor = new DescriptorDeProductoForm();
    $this->widgetSchemaDescriptor = $this->form_descriptor->getWidgetSchema();
    $this->widgetSchemaDescriptor->setNameFormat('descriptor_de_producto_'.$i.'[%s]');

    return $this->renderPartial('filaDescriptor', array('form_descriptor' => $this->form_descriptor, 'i' => $i));

  }
}
