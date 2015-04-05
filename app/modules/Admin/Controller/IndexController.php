<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url        http://www.magnxpyr.com
 */

namespace Modules\Admin\Controller;

use Engine\Controller;

class IndexController extends Controller {

    public function initialize() {
        $this->tag->setTitle('Admin Homepage');
        parent::setAdminEnvironment();
        parent::initialize();
    }

    public function indexAction() {

    }
}