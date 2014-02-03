<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new QuesosTestFunctional(new sfBrowser());
$browser->loadData();

$browser->
  info('1 - Seguridad del Web service')->

  info('  1.1 - Un código del producto es necesario para usar el servicio')->
  get('/api/productos.xml/foo')->
  with('response')->isStatusCode(404)->

  info('2 - El web service soporta formato XML')->
  get('/api/productos.xml/1')->
  with('request')->isFormat('xml')->
  with('response')->begin()->
    isValid()->
  end()->

  info('3 - El web service soporta formato JSON')->
  get('/api/productos.json/1')->
  with('request')->isFormat('json')->
  with('response')->matches('/"rama"\: "Queso"/')->

  info('4 - El web service soporta formato JSON')->
  get('/api/productos.json')->
  with('request')->isFormat('json')->
  with('response')->matches('/"rama"\: "Queso"/')
;