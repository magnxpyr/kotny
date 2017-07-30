<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Engine\Mvc\Controller;
use Phalcon\Mvc\View;

/**
 * Class ErrorController
 * @package Module\Core\Controllers
 */
class ErrorController extends Controller
{
    protected function initialize()
    {
        parent::initialize();
        $this->view->setViewsDir(APP_PATH . 'themes/'.DEFAULT_THEME.'/views/');
    }

    /**
     * Show 404 error
     */
    public function show404Action()
    {
        $this->response->setStatusCode(404, 'Page Not Found');

        if ($this->request->isAjax()) {
            $obj = new \stdClass();
            $obj->success = false;
            $obj->html = "Page not found";

            $this->view->disable();
            $this->response->setJsonContent($obj);
            $this->response->send();
            return false;
        }
    }

    /**
     * Show 503 error
     */
    public function show503Action()
    {
        $this->response->setStatusCode(503, 'Service unavailable');
    }
}