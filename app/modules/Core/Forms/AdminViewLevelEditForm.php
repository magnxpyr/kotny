<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Core\Models\Role;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Engine\Forms\Form;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminViewLevelEditForm
 * @package Core\Forms
 */
class AdminViewLevelEditForm extends Form
{
    /**
     * View level id
     * @var integer
     */
    public $viewLevel;

    /**
     * Initialize the form
     */
    public function initialize()
    {
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

        $role = Role::findFirstById($this->viewLevel)->getRoles();

        // Roles
        $roles = new Check('roles', [
            'class' => 'form-control'
        ]);
        $roles->setLabel($this->t->_('Roles'));
        $this->add($roles);
    }
}