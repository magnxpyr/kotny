<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Engine\Forms\Form;
use Engine\Package\PackageType;
use Module\Core\Models\Package;
use Module\Core\Models\ViewLevel;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminWidgetEditForm
 * @package Module\Core\Forms
 */
class AdminWidgetEditForm extends Form
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

        // Packages
        $package = new Select('package_id',
            Package::find("type = '" . PackageType::widget . "'"),
            ['using' => ['id', 'name'], 'useEmpty' => true, 'class' => 'form-control']
        );
        $package->setLabel($this->t->_('Widget'));
        $package->setFilters('int');
        $package->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Widget')])
            ])
        );
        $this->add($package);

        // Positions
        $position = new Select('position',
            $this->helper->getTemplateSections(),
            ['class' => 'form-control']
        );
        $position->setLabel($this->t->_('Position'));
        $position->setFilters('string');
        $position->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Position')])
            ])
        );
        $this->add($position);

        // Layout
        $layout = new Select('layout', [],
            [
                'useEmpty' => true,
                'emptyText' => 'None',
                'emptyValue' => null,
                'class' => 'form-control'
            ]
        );
        $layout->setLabel($this->t->_('Layout'));
        $layout->setFilters('string');
        $this->add($layout);

        // View
        $view = new Select('view', [],
            [
                'useEmpty' => true,
                'emptyText' => 'None',
                'emptyValue' => null,
                'value' => 'index',
                'class' => 'form-control'
            ]
        );
        $view->setLabel($this->t->_('View'));
        $view->setFilters('string');
        $this->add($view);

        // Ordering
        $order = new Select('ordering', [],
            ['using' => ['ordering', 'title'], 'class' => 'form-control']
        );
        $order->setLabel($this->t->_('Order'));
        $order->setFilters('int');
        $order->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Order')])
            ])
        );
        $this->add($order);

        // User view level
        $role = new Select('view_level',
            ViewLevel::find(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $role->setLabel($this->t->_('View Level'));
        $role->setFilters('int');
        $role->addValidator(
            new PresenceOf([
                'view_level' => $this->t->_('%field% is required', ['field' => $this->t->_('View Level')])
            ])
        );
        $this->add($role);

        $publishUp = new Text('publish_up', [
            'class' => 'form-control'
        ]);
        $publishUp->setLabel($this->t->_('Publish date'));
        $publishUp->setFilters('string');
        $publishUp->setAttribute('timestamp', true);
        $publishUp->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Publish date')])
            ])
        );
        $this->add($publishUp);

        $publishDown = new Text('publish_down', [
            'class' => 'form-control'
        ]);
        $publishDown->setLabel($this->t->_('End date'));
        $publishDown->setFilters('string');
        $publishDown->setAttribute('timestamp', time());
        $this->add($publishDown);

        // Show title
        $showTitle = new Select('show_title',
            $this->helper->getShowFields(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $showTitle->setLabel($this->t->_('Show title'));
        $showTitle->setFilters('int');
        $showTitle->addValidator(
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

        // Cache
        $cache = new Numeric('cache',
            ['class' => 'form-control']
        );
        $cache->setLabel($this->t->_('Cache'));
        $cache->setFilters('int');
        $this->add($cache);
    }
}