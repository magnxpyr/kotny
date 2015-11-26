<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Core\Models\Role;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Engine\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminMenuTypeCreateForm
 * @package Core\Forms
 */
class AdminUserEditForm extends Form
{
    /**
     * Initialize the Change Password Form
     */
    public function initialize()
    {
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