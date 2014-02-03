<?php
//date timezone para que sepa que estamos en santiago
date_default_timezone_set('America/Santiago');
require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->setWebDir($this->getRootDir().'/public_html/web/');
//    $this->setUploadDir($this->getRootDir().'public_html/web/uploads');
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfDoctrineGuardPlugin');
    $this->enablePlugins('sfDoctrineGuardPlugin');
    $this->enablePlugins(array('sfDoctrinePlugin','sfDoctrineGuardPlugin'));
    $this->enablePlugins('sfFormExtraPlugin');
    $this->enablePlugins('sfTCPDFPlugin');
    $this->enablePlugins('sfRutPlugin');
  }
}
