<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url        http://www.magnxpyr.com
 */

namespace Modules\Cms\Controllers;

use Phalcon\Mvc\View;

class ErrorController extends \Engine\Controller {

    public function show404Action() {
        $this->response->setStatusCode(404, 'Not Found');
        //$this->view->pick('error/show404');
    }
}