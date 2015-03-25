<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url        http://www.magnxpyr.com
 */

namespace Modules\Cms\Controllers;

class IndexController extends \Engine\Controller
{
    public function initialize() {
        $this->_getTranslation();
        $this->tag->setTitle('Welcome');
    }

    public function indexAction() {

    }
}

