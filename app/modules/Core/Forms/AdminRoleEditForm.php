<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Module\Core\Models\Role;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Engine\Forms\Form;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminRoleEditForm
 * @package Module\Core\Forms
 */
class AdminRoleEditForm extends Form
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

        // Parent
        $role = new Select('parent_id',
            Role::find(),
            [
                'using' => ['id', 'name'],
                'useEmpty' => true,
                'emptyValue' => 'None',
                'class' => 'form-control'
            ]
        );
        $role->setLabel($this->t->_('Parent'));
        $role->setFilters('int');
        $this->add($role);

        // Title
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

        // Description
        $description = new TextArea('description', [
            'rows' => 5,
            'cols' => 30,
            'class' => 'form-control'
        ]);
        $description->setLabel($this->t->_('Description'));
        $description->setFilters('string');
        $this->add($description);
    }
}