<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\Menu\Forms;

use Engine\Forms\Form;
use Module\Core\Models\MenuType;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminIndexForm
 * @package Widget\Menu\Forms
 */
class AdminIndexForm extends Form
{
    /**
     * Initialize the form
     */
    public function initialize()
    {
        // Menu
        $menu = new Select('_menu',
            MenuType::find(),
            ['using' => ['id', 'title']]
        );
        $menu->setLabel($this->t->_('Menu'));
        $menu->setFilters('int');
        $menu->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Menu')])
            ])
        );
        $this->add($menu);
    }
}