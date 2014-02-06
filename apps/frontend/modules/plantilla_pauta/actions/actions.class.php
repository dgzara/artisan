<?php

/**
 * plantilla_pauta actions.
 *
 * @package    quesos
 * @subpackage plantilla_pauta
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class plantilla_pautaActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $q = Doctrine_Query::create()
      ->from('PlantillaPauta');

      $pager = new sfDoctrinePager('PlantillaPauta', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('plantilla_pauta/show?id='.$v);
        $mod = $this->getController()->genUrl('plantilla_pauta/edit?id='.$v);
        $del = $this->getController()->genUrl('plantilla_pauta/delete?id='.$v);

	$aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getNombre(),
          "2" => $v->getRama()->getNombre(),
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
    $this->plantilla_pauta = Doctrine_Core::getTable('PlantillaPauta')->find(array($request->getParameter('id')));
    $this->plantilla_etapas = Doctrine_Core::getTable('PlantillaEtapa')->findByPlantillaPautaId($this->plantilla_pauta->getId());
    $this->plantilla_instrucciones = array();

    foreach($this->plantilla_etapas as $plantilla_etapa){
        $this->plantilla_instrucciones[] = Doctrine_Core::getTable('PlantillaInstruccion')->findByPlantillaEtapaId($plantilla_etapa->getId());
    }

    $this->forward404Unless($this->plantilla_pauta);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PlantillaPautaForm();
    $this->form_etapas = array();
    $this->form_instrucciones = array();
    $this->form_ingredientes = array();
    
    for($i=0; $i < 2; $i++){
        $this->form_ingredientes[$i] = new PlantillaIngredienteForm();
        $this->widgetSchemaIngrediente = $this->form_ingredientes[$i]->getWidgetSchema();
        $this->widgetSchemaIngrediente->setNameFormat('plantilla_ingrediente_'.$i.'[%s]');
    }

    for($i = 0; $i < 1; $i++){
        $this->form_etapas[$i] = new PlantillaEtapaForm();
        $this->widgetSchemaEtapa = $this->form_etapas[$i]->getWidgetSchema();
        $this->widgetSchemaEtapa['orden']->setDefault($i+1);
        $this->widgetSchemaEtapa->setNameFormat('plantilla_etapa_'.$i.'[%s]');

        $this->form_instrucciones[$i] = array();
        
        for($j = 0; $j < 4; $j++){
            $this->form_instrucciones[$i][$j] = new PlantillaInstruccionForm();
            $this->widgetSchemaInstruccion = $this->form_instrucciones[$i][$j]->getWidgetSchema();
            $this->widgetSchemaInstruccion['orden']->setDefault($j+1);
            $this->widgetSchemaInstruccion->setNameFormat('plantilla_instruccion_'.$i.'_'.$j.'[%s]');
        }
    }
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    // Contamos las etapas e instruccciones generadas
    $keys = array_keys($request->getPostParameters());

    $n_etapas = 0;                                                            // Numero de etapas
    $n_ingredientes = 0;                                                      // Número de ingredientes
    $etapas = array();                                                        // Número de instrucciones por etapas
    $nombre_etapas = array();
    $nombre_instrucciones = array();
    $nombre_ingredientes = array();

    foreach($keys as $key=>$val){
        if(substr_count($val, 'plantilla_etapa')){
            $n_etapas++;
            $etapas[$n_etapas - 1] = 0;
            $nombre_etapas[$n_etapas - 1] = $val;
            $nombre_instrucciones[$n_etapas - 1] = array();
        }
        elseif(substr_count($val, 'plantilla_instruccion')){
            $etapas[$n_etapas - 1]++;
            $nombre_instrucciones[$n_etapas - 1][$etapas[$n_etapas - 1]] = $val;
        }
        elseif(substr_count($val, 'plantilla_ingrediente')){
            $n_ingredientes++;
            $nombre_ingredientes[$n_ingredientes - 1] = $val;
        }
    }

    // Generamos el form
    
    $this->form = new PlantillaPautaForm();
    $this->form_etapas = array();
    $this->form_instrucciones = array();
    $this->form_ingredientes = array();
    
    for($i=0; $i < $n_ingredientes; $i++){
        $this->form_ingredientes[$i] = new PlantillaIngredienteForm();
        $this->widgetSchemaIngrediente = $this->form_ingredientes[$i]->getWidgetSchema();
        $this->widgetSchemaIngrediente->setNameFormat('plantilla_ingrediente_'.$i.'[%s]');
    }

    for($i = 0; $i < $n_etapas; $i++){
        $this->form_etapas[$i] = new PlantillaEtapaForm();
        $this->widgetSchemaEtapa = $this->form_etapas[$i]->getWidgetSchema();
        $this->widgetSchemaEtapa['orden']->setDefault($i+1);
        $this->widgetSchemaEtapa->setNameFormat('plantilla_etapa_'.$i.'[%s]');

        $this->form_instrucciones[$i] = array();
        
        for($j = 0; $j < $etapas[$i]; $j++){
            $this->form_instrucciones[$i][$j] = new PlantillaInstruccionForm();
            $this->widgetSchemaInstruccion = $this->form_instrucciones[$i][$j]->getWidgetSchema();
            $this->widgetSchemaInstruccion['orden']->setDefault($j+1);
            $this->widgetSchemaInstruccion->setNameFormat('plantilla_instruccion_'.$i.'_'.$j.'[%s]');
        }
    }

    $this->processForm($request, $this->form, $this->form_etapas, $this->form_instrucciones, $this->form_ingredientes);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($plantilla_pauta = Doctrine_Core::getTable('PlantillaPauta')->find(array($request->getParameter('id'))), sprintf('Object plantilla_pauta does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($plantilla_etapas = Doctrine_Core::getTable('PlantillaEtapa')->findByPlantillaPautaId($plantilla_pauta->getId()));
    $this->forward404Unless($plantilla_ingredientes = Doctrine_Core::getTable('PlantillaIngrediente')->findByPlantillaPautaId($plantilla_pauta->getId()));

    $this->form = new PlantillaPautaForm($plantilla_pauta);
    $this->form_etapas = array();
    $this->form_instrucciones = array();
    $this->form_ingredientes = array();
    
    for($i=0; $i < count($plantilla_ingredientes) ; $i++){
        $this->form_ingredientes[$i] = new PlantillaIngredienteForm($plantilla_ingredientes[$i]);
        $this->widgetSchemaIngrediente = $this->form_ingredientes[$i]->getWidgetSchema();
        $this->widgetSchemaIngrediente->setNameFormat('plantilla_ingrediente_'.$i.'[%s]');
    }

    for($i=0; $i < count($plantilla_etapas); $i++){
        $this->form_etapas[$i] = new PlantillaEtapaForm($plantilla_etapas[$i]);
        $this->widgetSchemaEtapa = $this->form_etapas[$i]->getWidgetSchema();
        $this->widgetSchemaEtapa['orden']->setDefault($i+1);
        $this->widgetSchemaEtapa->setNameFormat('plantilla_etapa_'.$i.'[%s]');

        $this->form_instrucciones[$i] = array();
        $this->forward404Unless($plantilla_instrucciones = Doctrine_Core::getTable('PlantillaInstruccion')->findByPlantillaEtapaId($plantilla_etapas[$i]->getId()));
        $m = count($plantilla_instrucciones);

        for($j = 0; $j < $m; $j++){
            $this->form_instrucciones[$i][$j] = new PlantillaInstruccionForm($plantilla_instrucciones[$j]);
            $this->widgetSchemaInstruccion = $this->form_instrucciones[$i][$j]->getWidgetSchema();
            $this->widgetSchemaInstruccion['orden']->setDefault($j+1);
            $this->widgetSchemaInstruccion->setNameFormat('plantilla_instruccion_'.$i.'_'.$j.'[%s]');
        }
    }
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($plantilla_pauta = Doctrine_Core::getTable('PlantillaPauta')->find(array($request->getParameter('id'))), sprintf('Object plantilla_pauta does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($plantilla_etapas = Doctrine_Core::getTable('PlantillaEtapa')->findByPlantillaPautaId($plantilla_pauta->getId()));
    $this->forward404Unless($plantilla_ingredientes = Doctrine_Core::getTable('PlantillaIngrediente')->findByPlantillaPautaId($plantilla_pauta->getId()));


    // Contamos las etapas e instruccciones generadas
    $keys = array_keys($request->getPostParameters());

    $n_etapas = 0;                                                            // Numero de etapas
    $n_ingredientes = 0;                                                      // Número de ingredientes
    $etapas = array();                                                        // Número de instrucciones por etapas
    $nombre_etapas = array();
    $nombre_instrucciones = array();
    $nombre_ingredientes = array();

    foreach($keys as $key=>$val){
        if(substr_count($val, 'plantilla_etapa')){
            $n_etapas++;
            $etapas[$n_etapas - 1] = 0;
            $nombre_etapas[$n_etapas - 1] = $val;
            $nombre_instrucciones[$n_etapas - 1] = array();
        }
        elseif(substr_count($val, 'plantilla_instruccion')){
            $etapas[$n_etapas - 1]++;
            $nombre_instrucciones[$n_etapas - 1][$etapas[$n_etapas - 1]] = $val;
        }
        elseif(substr_count($val, 'plantilla_ingrediente')){
            $n_ingredientes++;
            $nombre_ingredientes[$n_ingredientes - 1] = $val;
        }
    }

    // Generamos los forms
    $this->form = new PlantillaPautaForm($plantilla_pauta);
    $this->form_etapas = array();
    $this->form_instrucciones = array();
    $this->form_ingredientes = array();


    // Contamos la cantidad de ingredientes, si surge una nueva debemos crearla
    $m = count($plantilla_ingredientes);

    if($m < $n_ingredientes){
        for($i = $m; $i < $n_ingredientes; $i++){
            $plantilla_ingredientes[$i] = null;
        }
    }

    // Vemos cuál es el máximo
    $ing_max = max(array($m, $n_ingredientes));

    // Procesamos todas los ingredientes
    $num_form_ingredientes = 0;

    for($i=0; $i < $ing_max; $i++){
        
        $busco_ingrediente = 'plantilla_ingrediente_'.$i;
        
        // Busco si el ingrdediente está en el form, si no está debo borrarlo
        if(in_array($busco_ingrediente, $nombre_ingredientes)){
            $this->form_ingredientes[$num_form_ingredientes] = new PlantillaIngredienteForm($plantilla_ingredientes[$i]);
            $this->widgetSchemaIngrediente = $this->form_ingredientes[$i]->getWidgetSchema();
            $this->widgetSchemaIngrediente->setNameFormat('plantilla_ingrediente_'.$i.'[%s]');
            $num_form_ingredientes++;
        }
        else{
            $plantilla_ingredientes[$i]->delete();
        }
    }


    // Contamos la cantidad de etapas, si surge una nueva debemos crearla:
    $n = count($plantilla_etapas);

    if($n < $n_etapas){
        for($i = $n; $i < $n_etapas; $i++){
            $plantilla_etapas[$i] = null;
        }
    }

    // Vemos cuál es el máximo
    $etap_max = max(array($n, $n_etapas));

    // Procesamos todas las etapas
    $num_form_etapas = 0;

    for($i=0; $i < $etap_max; $i++){
        
        $busco_etapa = 'plantilla_etapa_'.$i;

        // Busco si la etapa está en el form, si no está debo borrarlo
        if(in_array($busco_etapa, $nombre_etapas)){
            $this->form_etapas[$num_form_etapas] = new PlantillaEtapaForm($plantilla_etapas[$i]);
            $this->widgetSchemaEtapa = $this->form_etapas[$num_form_etapas]->getWidgetSchema();
            $this->widgetSchemaEtapa['orden']->setDefault($i+1);
            $this->widgetSchemaEtapa->setNameFormat('plantilla_etapa_'.$i.'[%s]');
            $num_form_etapas++;
            
            $this->form_instrucciones[$num_form_etapas] = array();

            // Si la etapa es nueva, la instruccion
            if($plantilla_etapas[$i]->getId()){
                $plantilla_instrucciones = Doctrine_Core::getTable('PlantillaInstruccion')->findByPlantillaEtapaId($plantilla_etapas[$i]->getId());
            }
            else{
                $plantilla_instrucciones = null;
            }
            
            // Contamos la cantidad de etapas, si surge una nueva debemos crearla:
            $m = count($plantilla_instrucciones);

            $inst_max = max(array($m, $etapas[$i]));

            if($m < $etapas[$i]){
                for($k = $m; $k < $etapas[$i]; $k++){
                    $plantilla_instrucciones[$k] = null;
                }
            }

            $num_form_instrucciones = 0;
            
            // Procesa todos los forms
            for($j = 0; $j < $inst_max; $j++){

                $busco_instruccion = 'plantilla_instruccion_'.$i.'_'.$j;
                
                // Busco la instrucción, si no la encuentro la borro.
                if(in_array($busco_instruccion, $nombre_instrucciones[$num_form_etapas - 1])){
                    $this->form_instrucciones[$i][$num_form_instrucciones] = new PlantillaInstruccionForm($plantilla_instrucciones[$j]);
                    $this->widgetSchemaInstruccion = $this->form_instrucciones[$i][$num_form_instrucciones]->getWidgetSchema();
                    $this->widgetSchemaInstruccion['orden']->setDefault($j+1);
                    $this->widgetSchemaInstruccion->setNameFormat('plantilla_instruccion_'.$i.'_'.$j.'[%s]');
                    $num_form_instrucciones++;
                }
                else{
                    $plantilla_instrucciones[$j]->delete();
                }

            }
        }
        else{
            $plantilla_etapas[$i]->delete();
        }
    }

    $this->processForm($request, $this->form, $this->form_etapas, $this->form_instrucciones, $this->form_ingredientes);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($plantilla_pauta = Doctrine_Core::getTable('PlantillaPauta')->find(array($request->getParameter('id'))), sprintf('Object plantilla_pauta does not exist (%s).', $request->getParameter('id')));
    $plantilla_pauta->delete();

    $this->redirect('plantilla_pauta/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form, $form_etapas, $form_instrucciones, $form_ingredientes)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $plantilla_pauta = $form->save();

      // AGREMGAMOS LOS INGREDIENTES
      for($i = 0; $i < count($form_ingredientes); $i++){
        $this->processFormIngredientes($request, $form_ingredientes[$i], $plantilla_pauta);
      }

      // AGREGAMOS LAS ETAPAS
      for($i = 0; $i < count($form_etapas); $i++){
          $this->processFormEtapa($request, $form_etapas[$i], $form_instrucciones[$i], $plantilla_pauta);
      }
      $this->redirect('plantilla_pauta/index');
    }
    else{
        //echo 'fail';
    }
  }

  protected function processFormIngredientes(sfWebRequest $request, sfForm $form, $plantilla_pauta){
      $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
      if($form->isValid()){
          $plantilla_ingredientes = $form->updateObject();
          $plantilla_ingredientes->setPlantillaPautaId($plantilla_pauta->getId());
          if($plantilla_ingredientes->getInsumoId() != ''){
            $plantilla_ingredientes->save();
          }
      }
      else{
          echo 'fail ingredientes';
      }
  }


  protected function processFormEtapa(sfWebRequest $request, sfForm $form, $form_instrucciones, $plantilla_pauta){
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if($form->isValid()){
        $plantilla_etapa = $form->updateObject();
        $plantilla_etapa->setPlantillaPautaId($plantilla_pauta->getId());
        if($plantilla_etapa->getNombre() != ''){
            $plantilla_etapa->save();
            $m = count($form_instrucciones);

            for($j = 0; $j < $m; $j++){
                $this->processFormInstruccion($request, $form_instrucciones[$j], $plantilla_etapa);
            }
        }
    }
    else{
        echo 'fail Etapa';
    }
  }

  protected function processFormInstruccion(sfWebRequest $request, sfForm $form, $plantilla_etapa)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if($form->isValid()){
        $ins = $form->updateObject();
        $ins->setPlantillaEtapaId($plantilla_etapa->getId());
        if($ins->getDescripcion() != ''){
            $ins->save();
        }
    }
    else{
        echo 'fail Instruccion';
    }
  }

  public function executeCargarInstruccion(sfWebRequest $request)
  {
    $i = $request->getParameter('i');
    $j = $request->getParameter('j');

    $this->form_instrucciones = array();
    $this->form_instrucciones[$i] = array();
    $this->form_instrucciones[$i][$j] = new PlantillaInstruccionForm();
    $this->widgetSchemaInstruccion = $this->form_instrucciones[$i][$j]->getWidgetSchema();
    $this->widgetSchemaInstruccion['orden']->setDefault($j+1);
    $this->widgetSchemaInstruccion->setNameFormat('plantilla_instruccion_'.$i.'_'.$j.'[%s]');

    return $this->renderPartial('filaInstruccion', array('form_instrucciones' => $this->form_instrucciones, 'i'=>$i, 'j'=>$j));
  }

  public function executeCargarEtapa(sfWebRequest $request){
    $i = $request->getParameter('i');

    $this->form_etapas = array();
    $this->form_instrucciones = array();

    $this->form_etapas[$i] = new PlantillaEtapaForm();
    $this->widgetSchemaEtapa = $this->form_etapas[$i]->getWidgetSchema();
    $this->widgetSchemaEtapa['orden']->setDefault($i+1);
    $this->widgetSchemaEtapa->setNameFormat('plantilla_etapa_'.$i.'[%s]');

    $this->form_instrucciones[$i] = array();

    for($j = 0; $j < 4; $j++){
        $this->form_instrucciones[$i][$j] = new PlantillaInstruccionForm();
        $this->widgetSchemaInstruccion = $this->form_instrucciones[$i][$j]->getWidgetSchema();
        $this->widgetSchemaInstruccion['orden']->setDefault($j+1);
        $this->widgetSchemaInstruccion->setNameFormat('plantilla_instruccion_'.$i.'_'.$j.'[%s]');
    }

    return $this->renderPartial('filaEtapa', array('form_instrucciones' => $this->form_instrucciones, 'form_etapas' => $this->form_etapas, 'i'=> $i));
  }

  public function executeCargarIngrediente(sfWebRequest $request){
    $i = $request->getParameter('i');

    $this->form_ingredientes = array();
    $this->form_ingredientes[$i] = new PlantillaIngredienteForm();
    $this->widgetSchemaIngrediente = $this->form_ingredientes[$i]->getWidgetSchema();
    $this->widgetSchemaIngrediente->setNameFormat('plantilla_ingrediente_'.$i.'[%s]');

    return $this->renderPartial('filaIngrediente', array('form_ingredientes' => $this->form_ingredientes, 'i'=> $i));
  }

}
