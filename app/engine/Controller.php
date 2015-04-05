<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine;

class Controller extends \Phalcon\Mvc\Controller {

    protected function initialize() {

    }

    protected function setAdminEnvironment() {
        $this->view->setMainView(VIEW_PATH . 'admin');
        $this->view->setLayout('admin');
    }
}