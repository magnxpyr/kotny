<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

abstract class AdminController extends Controller {

    public function initialize() {
        $this->view->setMainView(THEMES_PATH . 'admin');
        $this->view->setLayout('admin');
    }

}