<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Engine\Controller;
use Phalcon\Mvc\View;

/**
 * Class ErrorController
 * @package Core\Controllers
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
    }

    /**
     * Show 503 error
     */
    public function show503Action()
    {
        $this->response->setStatusCode(503, 'Service unavailable');
    }
}