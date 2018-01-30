<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Module\Core\Models\Role;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Engine\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminViewLevelEditForm
 * @package Module\Core\Forms
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
        foreach (Role::find() as $role) {
            $roles = new Check("role".$role->getId(), [
                'name' => 'role[]',
                'value' => $role->getId()
            ]);
            $roles->setLabel($role->getName());
            $roles->setFilters('int');
            $this->add($roles);
        }
    }
}