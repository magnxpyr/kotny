<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Engine\Mvc\AdminController;

/**
 * Class AdminIndexController
 * @package Admin\Controllers
 */
class AdminIndexController extends AdminController
{
    /**
     * @inheritdoc
     */
    public function behaviors() {}

    /**
     * Display Admin Dashboard
     */
    public function indexAction()
    {
        $this->setTitle('Dashboard');
    }
}