<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Engine\Mvc\Controller;

/**
 * Class IndexController
 * @package Module\Core\Controllers
 */
class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->setLayout('homepage');
    }
}

