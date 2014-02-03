<?php

/**
 * sfGuardPermission filter form.
 *
 * @package    quesos
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardPermissionFormFilter extends PluginsfGuardPermissionFormFilter
{
  public function configure()
  {
                  unset(
                 $this['created_at'],
                 $this['description'],
                 $this['name'],
                 $this['updated_at'],
                 $this['expires_at'],
                 $this['last_login'],
                 $this['groups_list'],
                 $this['users_list']
            );
  }
}
