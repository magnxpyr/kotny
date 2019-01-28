<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Module\Core\Models\Role;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Engine\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminUserEditForm
 * @package Module\Core\Forms
 */
class AdminUserEditForm extends Form
{
    /**
     * Initialize the form
     */
    public function initialize()
    {
        parent::initialize();
        
        // Id
        $id = new Hidden('id');
        $id->setFilters('int');
        $this->add($id);

        // Title
        $username = new Text('username', [
            'class' => 'form-control'
        ]);
        $username->setLabel($this->t->_('Username'));
        $username->setFilters('string');
        $username->addValidators([
            new PresenceOf([
                'username' => $this->t->_('%field% is required', ['field' => $this->t->_('Username')])
            ])
        ]);
        $this->add($username);

        // Email
        $email = new Text('email', [
            'class' => 'form-control'
        ]);
        $email->setLabel($this->t->_('Email'));
        $email->setFilters('email');
        $email->addValidators([
            new PresenceOf([
                'email' => $this->t->_('%field% is required', ['field' => $this->t->_('Email')])
            ])
        ]);
        $this->add($email);

        // Name
        $email = new Text('name', [
            'class' => 'form-control'
        ]);
        $email->setLabel($this->t->_('Name'));
        $email->setFilters('string');
        $this->add($email);

        // Password
        $password = new Password('password', [
            'class' => 'form-control'
        ]);
        $password->setLabel($this->t->_('Password'));
        $password->setFilters('string');
        $password->addValidators([
            new PresenceOf([
                'password' => $this->t->_('%field% is required', ['field' => $this->t->_('Password')])
            ])
        ]);
        $this->add($password);

        // User role
        $role = new Select('role_id',
            Role::find(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $role->setLabel($this->t->_('Role'));
        $role->setFilters('int');
        $role->addValidators([
            new PresenceOf([
                'role_id' => $this->t->_('%field% is required', ['field' => $this->t->_('Role')])
            ])
        ]);
        $this->add($role);

        // Status
        $status = new Select('status',
            $this->helper->getArticleStatuses(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $status->setLabel($this->t->_('Status'));
        $status->setFilters('int');
        $status->addValidator(
            new PresenceOf([
                'status' => $this->t->_('%field% is required', ['field' => $this->t->_('Status')])
            ])
        );
        $this->add($status);
    }
}