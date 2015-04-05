<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url        http://www.magnxpyr.com
 */

namespace Modules\Cms\Controller;

class IndexController extends \Engine\Controller {

    public function initialize() {
        $this->tag->setTitle('Homepage');
        parent::initialize();
    }

    public function indexAction() {

    }
}

