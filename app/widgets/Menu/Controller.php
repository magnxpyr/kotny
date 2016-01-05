<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\Menu;

use Core\Models\Menu;
use Phalcon\Db\Column;
use Phalcon\Mvc\Model\EagerLoading\Loader;

/**
 * Class Controller
 * @package Widgets\Menu
 */
class Controller extends \Engine\Widget\Controller
{
    /**
     * Generate Menu based on menu Id
     */
    public function indexAction()
    {
        $menuElements = Loader::fromResultset(Menu::find([
            'conditions' => 'menu_type_id = ?1',
            'bind' => [1 => $this->getParam('id')],
            'bindTypes' => [Column::BIND_PARAM_INT],
            'order' => 'lft'
        ]), 'viewLevel');

        $this->viewWidget->setVar('menuElements', $menuElements);
    }
}