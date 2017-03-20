<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Engine\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminViewLevelEditForm
 * @package Core\Forms
 */
class AdminViewLevelEditForm extends Form
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

        // Name
        $name = new Text('name', [
            'class' => 'form-control'
        ]);
        $name->setLabel($this->t->_('Name'));
        $name->setFilters('string');
        $name->addValidators([
            new PresenceOf([
                'name' => $this->t->_('%field% is required', ['field' => $this->t->_('Name')])
            ])
        ]);
        $this->add($name);

        // Id
        $id = new Hidden('id');
        $id->setFilters('string');
        $this->add($id);

        // Roles
        foreach ($this->helper->acl->getRoles() as $role) {
            $roles = new Check("role".$role->getName(), [
                'name' => 'role[]',
                'value' => $role->getName()
            ]);
            $roles->setLabel($role->getDescription());
            $roles->setFilters('int');
            $this->add($roles);
        }
    }
}