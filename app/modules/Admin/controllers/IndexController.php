<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url        http://www.magnxpyr.com
 */

namespace Modules\Admin\Controllers;

class IndexController extends \Phalcon\Mvc\Controller {

    public function initialize() {
        $this->tag->setTitle('Welcome');
    }

    public function indexAction() {

    }
}