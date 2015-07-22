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
 * Class AdminMenuEditForm
 * @package Core\Forms
 */
class AdminMenuEditForm extends Form
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

        // Type
        $type = new Select('type',
            ['Path', 'Link'],
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $type->setLabel('Type');
        $type->setFilters('int');
        $type->addValidators([
            new PresenceOf([
                'type' => $this->t->_('Type is required')
            ])
        ]);
        $this->add($type);

        // Path
        $path = new Text('path', [
            'class' => 'form-control'
        ]);
        $path->setLabel('Path');
        $path->setFilters('string');
        $path->addValidators([
            new PresenceOf([
                'path' => $this->t->_('Path is required')
            ])
        ]);
        $this->add($path);

        // Link
        $link = new Text('link', [
            'class' => 'form-control'
        ]);
        $link->setLabel('Link');
        $link->setFilters('string');
        $link->addValidators([
            new PresenceOf([
                'link' => $this->t->_('Link is required')
            ])
        ]);
        $this->add($link);

        // Status
        $status = new Select('status',
            $this->helper->getUserRoles(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $status->setLabel('Status');
        $status->setFilters('int');
        $status->addValidators([
            new PresenceOf([
                'status' => $this->t->_('Status is required')
            ])
        ]);
        $this->add($status);

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