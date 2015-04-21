<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 * @authors     Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

class IndexController extends \Engine\Controller {

    public function indexAction() {
        $this->tag->setTitle('Homepage');
    }
}
