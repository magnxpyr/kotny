<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\Content\Controllers;

/**
 * Class Controller
 * @package Widget\Content\Controllers
 */
class Controller extends \Engine\Widget\Controller
{
    public function indexAction()
    {
        $this->viewWidget->setVar('content', $this->getParam("_content"));
    }
}