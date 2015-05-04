<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Phalcon\Mvc\View;

class ErrorController extends \Engine\Controller {

    protected function initialize() {
        $this->view->setViewsDir(APP_PATH . 'themes/'.DEFAULT_THEME.'/views/');
    }

    public function show404Action() {
        $this->response->setStatusCode(404, 'Page Not Found');
    }

    public function show503Action() {
        $this->response->setStatusCode(503, 'Service unavailable');
    }
}