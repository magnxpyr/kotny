<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminMenuTypeCreateForm
 * @package Core\Forms
 */
class AdminMenuTypeEditForm extends Form
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
        $title = new Text('title', [
            'class' => 'form-control'
        ]);
        $title->setLabel('Title');
        $title->setFilters('string');
        $title->addValidators([
            new PresenceOf([
                'title' => $this->t->_('Title is required')
            ])
        ]);
        $this->add($title);

        // User role
        $role = new Select('role_id',
            $this->helper->getUserRoles(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $role->setLabel('Role');
        $role->setFilters('int');
        $role->addValidators([
            new PresenceOf([
                'role_id' => $this->t->_('Role is required')
            ])
        ]);
        $this->add($role);

        // Description
        $description = new TextArea('description', [
            'rows' => 5,
            'cols' => 30,
            'class' => 'form-control'
        ]);
        $description->setLabel('Description');
        $description->setFilters('string');
        $this->add($description);
    }
}