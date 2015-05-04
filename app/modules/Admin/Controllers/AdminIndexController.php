<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Admin\Controllers;

use Engine\AdminController;

class AdminIndexController extends AdminController {

    public function indexAction() {
        $this->tag->setTitle('Admin Homepage');
    }
}