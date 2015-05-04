<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

abstract class AdminController extends Controller {

    protected function initialize() {
        $this->view->setMainView(THEMES_PATH . 'admin');
        $this->view->setLayout('admin');
    }

}