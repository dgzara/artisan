<?php

/**
 * sfGuardUser form.
 *
 * @package    quesos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserForm extends PluginsfGuardUserForm
{
  public function configure()
  {
        unset(
                $this['algorithm'], $this['salt'], $this['is_active'], $this['is_super_admin'], $this['created_at'], $this['updated_at'], $this['last_login'],
                $this['username']
        );

        $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
        $this->widgetSchema['groups_list'] = new sfWidgetFormChoice(array(
                'choices'  => Doctrine_Core::getTable('sfGuardGroup')->getGrupos(),
                'multiple' => true,
                'expanded' => true,
        ));
        $this->widgetSchema['permissions_list'] = new sfWidgetFormChoice(array(
                'choices'  => Doctrine_Core::getTable('sfGuardPermission')->getPermisos(),
                'multiple' => true,
                'expanded' => true,
        ));

        $this->validatorSchema['password'] =  new sfValidatorString(array('max_length' => 255 ));
        $this->validatorSchema['first_name'] = new sfValidatorString(array(), array('max_length' => 255, 'required' => "Campo Obligatorio."));
        $this->validatorSchema['last_name'] = new sfValidatorString(array(), array('max_length' => 255, 'required' => "Campo Obligatorio."));


        $this->validatorSchema['email_address'] = new sfValidatorAnd(array($this->validatorSchema['email_address'], new sfValidatorEmail(array(), array('required' => "Campo Obligatorio.", 'invalid' => "Debe ser un email válido.")),
        ));

        $this->validatorSchema['password']->setOption('required', true);
        $this->validatorSchema['password']->setOption('min_length', 6);

        $this->validatorSchema['password']->setMessage('required','Ingrese su Password');
        $this->validatorSchema['password']->setMessage('min_length','El largo mínimo del password es de 6 caracteres.');

        $this->widgetSchema['password_confirmation'] = new sfWidgetFormInputPassword();
        $this->validatorSchema['password_confirmation'] = clone $this->validatorSchema['password'];
        $this->widgetSchema->moveField('password_confirmation', sfWidgetFormSchema::AFTER, 'password');

        $sfValidatorAnd = new sfValidatorAnd($this->validatorSchema->getPostValidator());
        $sfValidatorAnd->addValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_confirmation', array(), array('invalid' => 'Los dos passwords deben ser iguales.')));
        $this->validatorSchema->setPostValidator($sfValidatorAnd);

        $this->widgetSchema->setLabels(array(
            'username' => 'Nombre de usuario:',
            'first_name' => 'Nombre*:',
            'last_name' => 'Apellido*:',
            'email_address' => 'Correo Electrónico*:',
            'password' => 'Contraseña*:',
            'password_confirmation' => 'Confirmación Contraseña*:',
            'groups_list'=> 'Grupos:',
            'permissions_list'=> 'Permisos:'
        ));
  }
}
