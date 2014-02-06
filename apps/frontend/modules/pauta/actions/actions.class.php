<?php

/**
 * pauta actions.
 *
 * @package    quesos
 * @subpackage pauta
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pautaActions extends sfActions
{
   // Un regalito del cliente
    
     /* ____________________ 
     * \         |         /                   
     *  \    C   |   D    /
     *   \       |       /
     *    \      |      / 
     *     \-----|-----/ 
     *      \    |    /  
     *       \ U | C / 
     *        \  |  /
     *         \ | /  
     *          \|/
     * by Hernan Vigil
     */

     // Los campeones dejan su huella
  /*
   *             UNIVERSIDAD DE CHILE
   *        ____________       ____________
   *       |            |     |            |
   *       |            |     |            |       
   *       |__        __|     |__        __|
   *          |       |          |       |
   *          |       |          |       |
   *          |       |          |       |
   *          |       |          |       |
   *          |       |          |       |
   *          |       |          |       |
   *          \        \ ______ /        /
   *           \                        /
   *            \                      /
   *             \                    /
   *              \__________________/
   */
    
  public function executeIndex(sfWebRequest $request)
  {
    

  }

  public function dateadd($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0) {
        $date_r = getdate(strtotime($date));
        $date_result = date("Y/m/d", mktime(($date_r["hours"] + $hh), ($date_r["minutes"] + $mn), ($date_r["seconds"] + $ss), ($date_r["mon"] + $mm), ($date_r["mday"] + $dd), ($date_r["year"] + $yy)));
        return $date_result;
    }

  public function executeShow(sfWebRequest $request)
  {
    $this->pauta = Doctrine_Core::getTable('Pauta')->find(array($request->getParameter('id')));
    $this->lotes = $this->pauta->getLotes();
    $this->instrucciones = $this->pauta->getInstrucciones();
    $this->plantilla_etapas = $this->pauta->getPlantillaPauta()->getPlantillaEtapas();
    $this->plantilla_columnas = Doctrine_Core::getTable('PlantillaPautaColumna')->findByPlantillaPautaId($this->pauta->getPlantillaPautaId());
    $this->plantilla_ingredientes = $this->pauta->getPlantillaPauta()->getPlantillaIngredientes();

    $this->array_instrucciones = array(array(array()));
    $this->plantilla_instrucciones = array();

    foreach($this->plantilla_etapas as $plantilla_etapa){
        $this->plantilla_instrucciones[$plantilla_etapa->getOrden()] = $plantilla_etapa->getPlantillaInstrucciones();
    }

    foreach($this->instrucciones as $instruccion){
        $i = $instruccion->getPlantillaInstruccion()->getPlantillaEtapa()->getOrden();
        $j = $instruccion->getPlantillaInstruccion()->getOrden();
        $this->array_instrucciones[$i][$j][] = $instruccion->getValor();
    }

    
    $this->forward404Unless($this->pauta);
  }
  
    public function executePreview(sfWebRequest $request)
  {
    $this->pauta = Doctrine_Core::getTable('Pauta')->find(array($request->getParameter('id')));
	$this->lotes = $this->pauta->getLotes();
    $this->forward404Unless($this->pauta);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PautaForm();
    $this->widgetSchema = $this->form->getWidgetSchema();
    $this->widgetSchema['plantilla_pauta_id']->setAttribute("onchange", "cargarPautaInstrucciones()");
    
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PautaForm();
    
    $this->processFormNew($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($pauta = Doctrine_Core::getTable('Pauta')->find(array($request->getParameter('id'))), sprintf('Object pauta does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($this->plantilla_etapas = Doctrine_Core::getTable('PlantillaEtapa')->findByPlantillaPautaId($pauta->getPlantillaPautaId()));
    $this->forward404Unless($this->plantillas_columnas = Doctrine_Core::getTable('PlantillaPautaColumna')->findByPlantillaPautaId($pauta->getPlantillaPautaId()));
    $this->forward404Unless($this->plantilla_ingredientes = Doctrine_Core::getTable('PlantillaIngrediente')->findByPlantillaPautaId($pauta->getPlantillaPautaId()));
    $this->forward404Unless($this->productos = Doctrine_Core::getTable('Producto')->findByRamaId($pauta->getPlantillaPauta()->getRamaId()));

    $this->form = new PautaForm($pauta);
    $this->form_instrucciones = array();
    $this->form_ingredientes = array();
    $this->form_lotes = array();

    // Proceso  los ingredientes
    $ingredientes = array();
    
    for($i=0; $i < count($this->plantilla_ingredientes); $i++){
      $ingredientes[$i] = Doctrine_Core::getTable('Ingrediente')->findOneByPlantillaIngredienteId($this->plantilla_ingredientes[$i]->getId());
      $this->form_ingredientes[$i] = new IngredienteForm($ingredientes[$i]);
      $this->widgetSchemaIngrediente = $this->form_ingredientes[$i]->getWidgetSchema();
      $this->widgetSchemaIngrediente['plantilla_ingrediente_id']->setDefault($this->plantilla_ingredientes[$i]);
      $this->widgetSchemaIngrediente->setNameFormat('ingrediente_'.$i.'[%s]');
    }

    // Proceso las instrucciones
    $this->plantilla_instrucciones = array();
    $instrucciones = array();

    for($i= 0; $i < count($this->plantilla_etapas); $i++){

        $instrucciones[$i] = array();
        $this->form_instrucciones[$i] = array();

        $this->forward404Unless($this->plantilla_instrucciones[$i] = Doctrine_Core::getTable('PlantillaInstruccion')->findByPlantillaEtapaId($this->plantilla_etapas[$i]->getId()));

        for($j = 0; $j < count($this->plantilla_instrucciones[$i]); $j++){
            $instrucciones[$i][$j] = array();
            $this->form_instrucciones[$i][$j] = array();

            for($k = 0; $k < count($this->plantillas_columnas); $k++){
                $instrucciones[$i][$j][$k] = Doctrine_Core::getTable('Instruccion')->findInstruccion($pauta->getId(), $this->plantilla_instrucciones[$i][$j]->getId() , $this->plantillas_columnas[$k]->getPlantillaColumnaId());
                $this->form_instrucciones[$i][$j][$k] = new InstruccionForm($instrucciones[$i][$j][$k]);
                $this->widgetSchemaInstruccion = $this->form_instrucciones[$i][$j][$k]->getWidgetSchema();
                $this->widgetSchemaInstruccion['plantilla_columna_id']->setDefault($this->plantillas_columnas[$k]->getPlantillaColumnaId());
                $this->widgetSchemaInstruccion->setNameFormat('instruccion_'.$i.'_'.$j.'_'.$k.'[%s]');
            }
        }
    }

    // Procesamos los productos y lotes
    foreach($this->productos as $producto){
          $lote = Doctrine_Core::getTable('Lote')->findByDql('producto_id = ? AND pauta_id = ?', array($producto->getId(), $pauta->getId()))->getFirst();
          $this->form_lotes[$producto->getId()] = new LoteForm($lote);
          $this->widgetSchemaLote = $this->form_lotes[$producto->getId()]->getWidgetSchema();
          $this->widgetSchemaLote->setNameFormat('lote_'.$producto->getId().'[%s]');
          $this->widgetSchemaLote['producto_id'] = new sfWidgetFormInputText();
          $this->widgetSchemaLote['producto_id']->setLabel($producto->getNombreCompleto());
          $this->widgetSchemaLote['producto_id']->setDefault($producto->getId());
          $this->widgetSchemaLote['producto_id']->setAttribute('type', 'hidden');
          $this->widgetSchemaLote['producto_id']->setHidden(true);
          $this->widgetSchemaLote['numero'] = new sfWidgetFormInputText();
          $this->widgetSchemaLote['numero']->setAttribute('type', 'hidden');
          $this->widgetSchemaLote['numero']->setHidden(true);
          $this->widgetSchemaLote['cantidad']->setDefault(0);
          $this->widgetSchemaLote['cantidad_actual'] = new sfWidgetFormInputText();
          $this->widgetSchemaLote['cantidad_actual']->setAttribute('type', 'hidden');
          $this->widgetSchemaLote['cantidad_actual']->setHidden(true);
      }
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($pauta = Doctrine_Core::getTable('Pauta')->find(array($request->getParameter('id'))), sprintf('Object pauta does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($this->plantilla_etapas = Doctrine_Core::getTable('PlantillaEtapa')->findByPlantillaPautaId($pauta->getPlantillaPautaId()));
    $this->forward404Unless($this->productos = Doctrine_Core::getTable('Producto')->findByRamaId($pauta->getPlantillaPauta()->getRamaId()));
    $this->forward404Unless($this->plantillas_columnas = Doctrine_Core::getTable('PlantillaPautaColumna')->findByPlantillaPautaId($pauta->getPlantillaPautaId()));
    $this->forward404Unless($this->plantilla_ingredientes = Doctrine_Core::getTable('PlantillaIngrediente')->findByPlantillaPautaId($pauta->getPlantillaPautaId()));

    $this->form = new PautaForm($pauta);

    $this->processFormEdit($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($pauta = Doctrine_Core::getTable('Pauta')->find(array($request->getParameter('id'))), sprintf('Object pauta does not exist (%s).', $request->getParameter('id')));
    $pauta->delete();

    $this->redirect('pauta/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $pauta = $form->save();

      $this->redirect('pauta/edit?id='.$pauta->getId());
    }
  }

  protected function processFormNew(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $pauta = $form->save();
      $rama_id = $pauta->getPlantillaPauta()->getRamaId();

      $plantilla_ingredientes = Doctrine_Core::getTable('PlantillaIngrediente')->findByPlantillaPautaId($pauta->getPlantillaPautaId());
      $plantilla_etapas = Doctrine_Core::getTable('PlantillaEtapa')->findByPlantillaPautaId($pauta->getPlantillaPautaId());
      $plantillas_columnas = Doctrine_Core::getTable('PlantillaPautaColumna')->findByPlantillaPautaId($pauta->getPlantillaPautaId());

      $this->form_ingredientes = array();

      for($i=0; $i < count($plantilla_ingredientes); $i++){
          $this->form_ingredientes[$i] = new IngredienteForm();
          $this->widgetSchemaIngrediente = $this->form_ingredientes[$i]->getWidgetSchema();
          $this->widgetSchemaIngrediente['plantilla_ingrediente_id']->setDefault($this->plantilla_ingredientes[$i]);
          $this->widgetSchemaIngrediente->setNameFormat('ingrediente_'.$i.'[%s]');
          $this->processFormIngrediente($request, $this->form_ingredientes[$i], $pauta);
      }


      $productos = Doctrine_Core::getTable('Producto')->findByRamaId($rama_id);

      $this->form_instrucciones = array();
      $this->form_lotes = array();
      $this->plantilla_instrucciones = array();
      
      for($i= 0; $i < count($plantilla_etapas); $i++){
          $this->form_instrucciones[$i] = array();
          $this->forward404Unless($this->plantilla_instrucciones[$i] = Doctrine_Core::getTable('PlantillaInstruccion')->findByPlantillaEtapaId($plantilla_etapas[$i]->getId()));

          for($j = 0; $j < count($this->plantilla_instrucciones[$i]); $j++){

              $this->form_instrucciones[$i][$j] = array();

              for($k = 0; $k < count($plantillas_columnas); $k++){
                  $this->form_instrucciones[$i][$j][$k] = new InstruccionForm();
                  $this->widgetSchemaInstruccion = $this->form_instrucciones[$i][$j][$k]->getWidgetSchema();
                  $this->widgetSchemaInstruccion['plantilla_columna_id']->setDefault($plantillas_columnas[$k]->getPlantillaColumnaId());
                  $this->widgetSchemaInstruccion->setNameFormat('instruccion_'.$i.'_'.$j.'_'.$k.'[%s]');
                  $this->processFormInstruccion($request, $this->form_instrucciones[$i][$j][$k], $pauta, $this->plantilla_instrucciones[$i][$j]);
              }
          }
      }
      

           
      $this->total = 0;
      
      foreach($productos as $producto){
          $this->form_lotes[$producto->getId()] = new LoteForm();
          $this->widgetSchemaLote = $this->form_lotes[$producto->getId()]->getWidgetSchema();
          $this->widgetSchemaLote->setNameFormat('lote_'.$producto->getId().'[%s]');
          $this->widgetSchemaLote['producto_id']->setLabel($producto->getNombreCompleto());
          $this->widgetSchemaLote['producto_id']->setDefault($producto->getId());
          $this->widgetSchemaLote['cantidad']->setDefault(0);   
      
          //Aqui se calculan los porcentajes de la tina que se producen para cada lote. 
          $q2 = Doctrine_Query::create()
                ->select('p.id, p.presentacion')
                ->from('Producto p')
                ->where('p.id = ?',$producto->getId());
            $temp = $q2->fetchOne();
            $prodgramaje = $temp->getPresentacion();
          
          $this->contarPorcentaje($request, $this->form_lotes[$producto->getId()], $prodgramaje);
      }
      
      foreach($productos as $producto){
          $this->form_lotes[$producto->getId()] = new LoteForm();
          $this->widgetSchemaLote = $this->form_lotes[$producto->getId()]->getWidgetSchema();
          $this->widgetSchemaLote->setNameFormat('lote_'.$producto->getId().'[%s]');
          $this->widgetSchemaLote['producto_id']->setLabel($producto->getNombreCompleto());
          $this->widgetSchemaLote['producto_id']->setDefault($producto->getId());
          $this->widgetSchemaLote['cantidad']->setDefault(0);
          
          $this->processFormNewLote($request, $this->form_lotes[$producto->getId()], $pauta);
      }

      $this->redirect('pauta/index');
    }
  }

  protected function processFormEdit(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $pauta = $form->save();

      $this->form_instrucciones = array();
      $this->form_ingredientes = array();
      $this->form_lotes = array();
      
      $instrucciones = array();
      $ingredientes = array();

      // Proceso los Ingredientes
      $ingredientes = array();

      for($i=0; $i < count($this->plantilla_ingredientes); $i++){
          $ingredientes[$i] = Doctrine_Core::getTable('Ingrediente')->findOneByPlantillaIngredienteId($this->plantilla_ingredientes[$i]->getId());
          $this->form_ingredientes[$i] = new IngredienteForm($ingredientes[$i]);
          $this->widgetSchemaIngrediente = $this->form_ingredientes[$i]->getWidgetSchema();
          $this->widgetSchemaIngrediente['plantilla_ingrediente_id']->setDefault($this->plantilla_ingredientes[$i]);
          $this->widgetSchemaIngrediente->setNameFormat('ingrediente_'.$i.'[%s]');
          $this->processFormIngrediente($request, $this->form_ingredientes[$i], $pauta);
      }

      // Proceso las etapas
      for($i= 0; $i < count($this->plantilla_etapas); $i++){
          
          $instrucciones[$i] = array();
          $this->form_instrucciones[$i] = array();

          $this->plantilla_instrucciones[$i] = Doctrine_Core::getTable('PlantillaInstruccion')->findByPlantillaEtapaId($this->plantilla_etapas[$i]->getId());
          for($j = 0; $j < count($this->plantilla_instrucciones[$i]); $j++){

              $instrucciones[$i][$j] = array();
              $this->form_instrucciones[$i][$j] = array();

              for($k = 0; $k < count($this->plantillas_columnas); $k++){
                  $instrucciones[$i][$j][$k] = Doctrine_Core::getTable('Instruccion')->findInstruccion($pauta->getId(), $this->plantilla_instrucciones[$i][$j]->getId() , $this->plantillas_columnas[$k]->getPlantillaColumnaId());
                  $this->form_instrucciones[$i][$j][$k] = new InstruccionForm($instrucciones[$i][$j][$k]);
                  $this->widgetSchemaInstruccion = $this->form_instrucciones[$i][$j][$k]->getWidgetSchema();
                  $this->widgetSchemaInstruccion['plantilla_columna_id']->setDefault($this->plantillas_columnas[$k]->getPlantillaColumnaId());
                  $this->widgetSchemaInstruccion->setNameFormat('instruccion_'.$i.'_'.$j.'_'.$k.'[%s]');
                  $this->processFormInstruccion($request, $this->form_instrucciones[$i][$j][$k], $pauta, $this->plantilla_instrucciones[$i][$j]);
              }
          }
      }

      // Procesamos los productos y lotes
      foreach($this->productos as $producto){
            $lote = Doctrine_Core::getTable('Lote')->findByDql('producto_id = ? AND pauta_id = ?', array($producto->getId(), $pauta->getId()))->getFirst();
            $this->form_lotes[$producto->getId()] = new LoteForm($lote);
            $this->widgetSchemaLote = $this->form_lotes[$producto->getId()]->getWidgetSchema();
            $this->widgetSchemaLote->setNameFormat('lote_'.$producto->getId().'[%s]');
            $this->widgetSchemaLote['producto_id'] = new sfWidgetFormInputText();
            $this->widgetSchemaLote['producto_id']->setLabel($producto->getNombreCompleto());
            $this->widgetSchemaLote['producto_id']->setDefault($producto->getId());
            $this->widgetSchemaLote['producto_id']->setAttribute('type', 'hidden');
            $this->widgetSchemaLote['producto_id']->setHidden(true);
            $this->widgetSchemaLote['numero']->setHidden(true);
            $this->widgetSchemaLote['cantidad']->setDefault(0);
            $this->processFormLote($request, $this->form_lotes[$producto->getId()], $pauta);
      }

      $this->redirect('pauta/index');
    
      
    }
  }
  
  protected function processFormInstruccion(sfWebRequest $request, sfForm $form, $pauta, $plantilla_instruccion)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if($form->isValid()){
        $p = $form->updateObject();
        $p->setPautaId($pauta->getId());
        $p->setPlantillaInstruccionId($plantilla_instruccion->getId());
        $p->save();
    }
    else{
        echo 'fail Instruccion';
    }
  }

  protected function processFormLote(sfWebRequest $request, sfForm $form, $pauta)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if($form->isValid()){
        $p = $form->updateObject();
        $p->setPautaId($pauta->getId());
        $p->setFechaElaboracion($pauta->getFecha());
        $p->setAccion('A Madurar');
        if($p->getCantidad() != 0){
            $lote = $p->save();
        }
        else{
            // Si existe y es cero, debemos eliminarlo.
            if($p->getId()){
                $lote = Doctrine_Core::getTable('Lote')->findOneById($p->getId());
                if($lote){
                    $lote->delete();
                }
            }
        }
    }
    else{
        echo 'fail Lote';
    }
  }

  protected function processFormNewLote(sfWebRequest $request, sfForm $form, $pauta)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if($form->isValid()){
        $p = $form->updateObject();
        $p->setPautaId($pauta->getId());
        $p->setFechaElaboracion($pauta->getFecha());
        $p->setAccion('A Madurar');
        if($p->getCantidad() != 0){
            $p->save();
    
            $lote = Doctrine_Core::getTable('Lote')->getLastLote();

            //aqui se crea el objeto que guardara los costos directos.
            //obtenemos id's de insumos unidos a las respectivas cantidades usadas.
            $q = Doctrine_Query::create()
                ->select('i.id as idinsumo, ing.cantidad_usada, pi.id')
                ->from('Ingrediente ing')
                ->leftJoin('ing.PlantillaIngrediente pi')
                ->leftJoin('pi.Insumo i')
                ->where('ing.pauta_id = ?',$lote->pauta_id);           
            $insumos = $q->execute();
            
            //Aqui buscamos el gramaje del producto a registrar.
            $q2 = Doctrine_Query::create()
                ->select('p.id, p.presentacion')
                ->from('Producto p')
                ->where('p.id = ?',$lote->producto_id);
            $temp = $q2->fetchOne();
            $prodgramaje = $temp->getPresentacion();
            
            //Ahora tenemos que calcular el costo directo de este lote. La formula es asi:
            //Sumatoria sobre los insumos usados en tina: S = [kg totales en tina del insumo]*[Precio de Insumo por Kg]
            // Luego calcular el porcentaje de producto que se obtuvo de esa tina:
            // %P = [unidades del producto_id]
            
            foreach($insumos as $insumo)
            {
                //costodirecto = lote_id, insumo_id, tipo_costo, fecha, costo
                $costodirecto= new CostosDirectos();
                $costodirecto->setLoteId($lote->getId());
                $costodirecto->setInsumoId($insumo->getIdinsumo());
                $costodirecto->setTipoCosto('elab');
                $costodirecto->setFecha($pauta->getFecha());
                $costodirecto->setProductoId($lote->getProductoId());
                
                
                $preciou = Doctrine_Core::getTable('OrdenCompraInsumo')->getUltimoPrecioUnitario($insumo->getIdinsumo());
                $costodirecto->setPrecioUnitario($preciou);
                
                //Aqui seteamos el costo directo, que no es tan evidente.
                $cantinsumo = $insumo->getCantidadUsada();
                $cantproducida = $lote->getCantidad();
                $gramosproducidos = $cantproducida*$prodgramaje;
                $porcentaje = $gramosproducidos/($this->total);
                $costoelab = ($preciou * $cantinsumo) * $porcentaje;
                $costodirecto->setValor($costoelab);
                $costodirecto->save();
             }
            

             
            
            
            
            
            
            
            
        }
    }
    else{
        echo 'fail Lote';
    }
  }
  
  protected function contarPorcentaje(sfWebRequest $request, sfForm $form, $gramaje)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if($form->isValid()){
        $p = $form->updateObject();
        if($p->getCantidad() != 0){
            
            $nuevo = $gramaje*($p->getCantidad());
            
            $this->total = $this->total + $nuevo;
        }
    }
    else{
        echo 'fail porcentaje';
    }
  }
  
  protected function processFormIngrediente(sfWebRequest $request, sfForm $form, $pauta)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if($form->isValid()){
        $p = $form->updateObject();
        $p->setPautaId($pauta->getId());
        $p->save();
    }
    else{
        echo 'fail Ingrediente';
    }
  }

  public function executeCargarInstrucciones(sfWebRequest $request){
      $plantilla_pauta_id = $request->getParameter('plantilla_pauta_id');

      $plantilla_pauta = Doctrine_Core::getTable('PlantillaPauta')->findOneById($plantilla_pauta_id);


      
      if($plantilla_pauta_id!=0)
        $rama_id = $plantilla_pauta->getRamaId();
      
      $this->plantilla_ingredientes = Doctrine_Core::getTable('PlantillaIngrediente')->findByPlantillaPautaId($plantilla_pauta_id);
      $this->plantilla_etapas = Doctrine_Core::getTable('PlantillaEtapa')->findByPlantillaPautaId($plantilla_pauta_id);
      $this->plantillas_columnas = Doctrine_Core::getTable('PlantillaPautaColumna')->findByPlantillaPautaId($plantilla_pauta_id);
      $this->productos = Doctrine_Core::getTable('Producto')->findByRamaId($rama_id);

      $this->plantilla_instrucciones = array();

      $this->form_ingredientes = array();
      $this->form_instrucciones = array();
      $this->form_lotes = array();

      for($i = 0; $i < count($this->plantilla_ingredientes); $i++){
          $this->form_ingredientes[$i] = new IngredienteForm();
          $this->widgetSchemaIngrediente = $this->form_ingredientes[$i]->getWidgetSchema();
          $this->widgetSchemaIngrediente['plantilla_ingrediente_id']->setDefault($this->plantilla_ingredientes[$i]);
          $this->widgetSchemaIngrediente->setNameFormat('ingrediente_'.$i.'[%s]');
      }

      for($i= 0; $i < count($this->plantilla_etapas); $i++){
          $this->form_instrucciones[$i] = array();
          $this->plantilla_instrucciones[$i] = Doctrine_Core::getTable('PlantillaInstruccion')->findByPlantillaEtapaId($this->plantilla_etapas[$i]->getId());

          for($j = 0; $j < count($this->plantilla_instrucciones[$i]); $j++){
              // Genero el formulario
              $this->form_instrucciones[$i][$j] = array();

              for($k = 0; $k < count($this->plantillas_columnas); $k++){
                  $this->form_instrucciones[$i][$j][$k] = new InstruccionForm();
                  $this->widgetSchemaInstruccion = $this->form_instrucciones[$i][$j][$k]->getWidgetSchema();
                  $this->widgetSchemaInstruccion['plantilla_columna_id']->setDefault($this->plantillas_columnas[$k]->getPlantillaColumnaId());
                  $this->widgetSchemaInstruccion->setNameFormat('instruccion_'.$i.'_'.$j.'_'.$k.'[%s]');
              }
          }
      }

      foreach($this->productos as $producto){
          $this->form_lotes[$producto->getId()] = new LoteForm();
          $this->widgetSchemaLote = $this->form_lotes[$producto->getId()]->getWidgetSchema();
          $this->widgetSchemaLote->setNameFormat('lote_'.$producto->getId().'[%s]');
          $this->widgetSchemaLote['producto_id'] = new sfWidgetFormInputText();
          $this->widgetSchemaLote['producto_id']->setLabel($producto->getNombreCompleto());
          $this->widgetSchemaLote['producto_id']->setDefault($producto->getId());
          $this->widgetSchemaLote['producto_id']->setAttribute('type', 'hidden');
          $this->widgetSchemaLote['producto_id']->setHidden(true);
          $this->widgetSchemaLote['numero'] = new sfWidgetFormInputText();
          $this->widgetSchemaLote['numero']->setAttribute('type', 'hidden');
          $this->widgetSchemaLote['numero']->setHidden(true);
          $this->widgetSchemaLote['cantidad']->setDefault(0);
          $this->widgetSchemaLote['cantidad_actual'] = new sfWidgetFormInputText();
          $this->widgetSchemaLote['cantidad_actual']->setAttribute('type', 'hidden');
          $this->widgetSchemaLote['cantidad_actual']->setHidden(true);
      }

      return $this->renderPartial('plantilla', array('form_instrucciones' => $this->form_instrucciones, 'form_lotes'=> $this->form_lotes, 'plantilla_instrucciones' => $this->plantilla_instrucciones, 'plantilla_etapas' => $this->plantilla_etapas, 'plantillas_columnas' => $this->plantillas_columnas, 'form_ingredientes' => $this->form_ingredientes, 'plantilla_ingredientes' => $this->plantilla_ingredientes));
  }

  public function executeFiltrado(sfWebRequest $request){
    if ($request->isXmlHttpRequest())
    {
      $items = $request->getParameter('items');
      $desde = $items[0]['name'];
      $hasta = $items[1]['name'];

      $q = Doctrine_Query::create()
           ->from('Pauta')
           ->where('fecha BETWEEN ? AND ?', array($desde, $hasta))
           ->orderBy('fecha DESC');


      $pager = new sfDoctrinePager('Pauta', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('pauta/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('pauta/edit?id='.$v->getId());

        $aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getPlantillaPauta()->getNombre(),
          "2" => $v->getDateTimeObject('fecha')->format('d M Y'),
          "3" => '<a class="jt" rel="/quesosar/web/pauta/preview/'.$v->getId().'" title="Pauta '.$v->getPlantillaPauta()->getNombre().'" href="'.$ver.'"><img src="../images/tools/icons/event_icons/ico-story.png" border="0" /></a>',
          "4" => '<a href="'.$mod.'"><img src="../images/tools/icons/event_icons/ico-edit.png" border="0"></a></a>',
          "5" => $items[0]['name'],
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

  public function executeGet_data(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
//      $items = $request->getParameter('items');
//      $desde = $items[0]['name'];
//      $hasta = $items[1]['name'];

        $desde = '2000/01/01';
        $hasta = '2100/01/01';

      $q = Doctrine_Query::create()
           ->from('Pauta')
           ->where('fecha BETWEEN ? AND ?', array($desde, $hasta))
           ->orderBy('fecha DESC');

      $pager = new sfDoctrinePager('Pauta', $request->getParameter('iDisplayLength'));
      $pager->setQuery($q);
      $pager->setPage($request->getParameter('page', 1));
      $pager->init();

      $aaData = array();
      $list = $pager->getResults();
      foreach ($list as $v)
      {
        $ver = $this->getController()->genUrl('pauta/show?id='.$v->getId());
        $mod = $this->getController()->genUrl('pauta/edit?id='.$v->getId());

        $aaData[] = array(
          "0" => $v->getId(),
          "1" => $v->getPlantillaPauta()->getNombre(),
          "2" => $v->getDateTimeObject('fecha')->format('d-m-Y'),
          "3" => '<a class="jt" rel="/web/pauta/preview/'.$v->getId().'" title="Pauta '.$v->getPlantillaPauta()->getNombre().'" href="'.$ver.'"><img src="../images/tools/icons/event_icons/ico-story.png" border="0" /></a>',
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

}
