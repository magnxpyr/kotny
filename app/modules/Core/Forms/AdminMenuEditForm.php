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
use Engine\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Url;

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
        $title->setLabel($this->t->_('Title'));
        $title->setFilters('string');
        $title->addValidator(
            new PresenceOf([
                'title' => $this->t->_('%field% is required', ['field' => $this->t->_('Title')])
            ])
        );
        $this->add($title);

        // Type
        $type = new Select('type',
            [$this->t->_('Path'), $this->t->_('Link')],
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $type->setLabel($this->t->_('Type'));
        $type->setFilters('int');
        $type->addValidator(
            new PresenceOf([
                'type' => $this->t->_('%field% is required', ['field' => $this->t->_('Type')])
            ])
        );
        $this->add($type);

        // Path
        $path = new Text('path', [
            'class' => 'form-control'
        ]);
        $path->setLabel($this->t->_('Path'));
        $path->setFilters('string');
        if ($this->request->getPost('path') == 0) {
            $path->addValidator(
                new PresenceOf([
                    'path' => $this->t->_('%field% is required', ['field' => $this->t->_('Path')])
                ])
            );
        }
        $this->add($path);

        // Link
        $link = new Text('link', [
            'class' => 'form-control'
        ]);
        $link->setLabel($this->t->_('Link'));
        $link->setFilters('string');
        if ($this->request->getPost('path') == 1) {
            $link->addValidators([
                new Url([
                    'link' => $this->t->_('%field% has to be and URL', ['field' => $this->t->_('Link')])
                ]),
                new PresenceOf([
                    'link' => $this->t->_('%field% is required', ['field' => $this->t->_('Link')])
                ])
            ]);
        }
        $this->add($link);

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

        // User role
        $role = new Select('role_id',
            $this->helper->getUserRoles(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $role->setLabel($this->t->_('Role'));
        $role->setFilters('int');
        $role->addValidator(
            new PresenceOf([
                'role_id' => $this->t->_('%field% is required', ['field' => $this->t->_('Role')])
            ])
        );
        $this->add($role);

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