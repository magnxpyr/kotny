<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\Menu;
use Core\Models\Menu;
use Phalcon\Db\Column;

/**
 * Class Controller
 * @package Widgets\Menu
 */
class Controller extends \Engine\Widget\Controller
{
    /**
     * Generate Menu based on menuId
     */
    public function indexAction()
    {
        $menuElements = Menu::find([
            'conditions' => 'menu_type_id = ?1',
            'bind' => [1 => $this->getParam('menuId')],
            'bindTypes' => [Column::BIND_PARAM_INT],
            'order' => 'lft'
        ]);
        $this->viewWidget->setVar('menuElements', $menuElements);
    }
}