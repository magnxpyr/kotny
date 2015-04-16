<?php
/*
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url        http://www.magnxpyr.com
 */

namespace Core\Controllers;

use Phalcon\Mvc\View;

class ErrorController extends \Engine\Controller {

    public function show404Action() {
        $this->response->setStatusCode(404, 'Not Found');
    }

    public function show500Action() {
        $this->response->setStatusCode(500, 'Internal Server Error');
    }
}