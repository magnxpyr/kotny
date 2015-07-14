<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Admin\Controllers;

use Engine\Mvc\AdminController;

class AdminIndexController extends AdminController {

    public function indexAction() {
        $this->setTitle('Admin Homepage');
    }
}