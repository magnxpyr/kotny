<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Widget;

/**
 * Class Controller
 * @package Engine\Widget
 */
class Controller extends \Phalcon\Mvc\Controller
{
    /**
     * @var \Phalcon\Mvc\View() $viewWidget
     */
    protected $viewWidget;

    public function initialize()
    {
        $this->viewWidget = $this->di->get('viewWidget');
    }
}