<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Module\Core\Models\MenuType;
use Module\Core\Models\ViewLevel;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Engine\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminMenuEditForm
 * @package Module\Core\Forms
 */
class AdminMenuEditForm extends Form
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

        // Type
        $menu_type = new Select('menu_type_id',
            MenuType::find(),
            ['using' => ['id', 'title'], 'class' => 'form-control']
        );
        $menu_type->setLabel($this->t->_('Menu Type'));
        $menu_type->setFilters('int');
        $menu_type->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Menu Type')])
            ])
        );
        $this->add($menu_type);

        // Prepend html object before title
        $prepend = new Text('prepend', [
            'class' => 'form-control'
        ]);
        $prepend->setLabel($this->t->_('Prepend Class'));
        $prepend->setFilters('string');
        $this->add($prepend);

        // Title
        $title = new Text('title', [
            'class' => 'form-control'
        ]);
        $title->setLabel($this->t->_('Title'));
        $title->setFilters('string');
        $title->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Title')])
            ])
        );
        $this->add($title);


        // Path
        $path = new Text('path', [
            'class' => 'form-control'
        ]);
        $path->setLabel($this->t->_('Path'));
        $path->setFilters('string');
        $path->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Path')])
            ])
        );
        $this->add($path);

        // Show title
        $showTitle = new Check('show_title', ['value' => 1]);
        $showTitle->setLabel($this->t->_('Show title'));
        $showTitle->setDefault(1);
        $showTitle->setFilters('int');
        $path->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Show title')])
            ])
        );
        $this->add($showTitle);

        // Status
        $status = new Select('status',
            $this->helper->getArticleStatuses(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $status->setLabel($this->t->_('Status'));
        $status->setFilters('int');
        $status->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Status')])
            ])
        );
        $this->add($status);

        // User view level
        $role = new Select('view_level',
            ViewLevel::find(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $role->setLabel($this->t->_('View Level'));
        $role->setFilters('int');
        $role->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('View Level')])
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

    public function bind(array $data, $entity, $whitelist = null)
    {
        if (!isset($data['view_title'])) {
            $entity->setShowTitle(0);
        }

        parent::bind($data, $entity, $whitelist);
    }
}