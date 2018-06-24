<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Engine\Mvc\AdminController;
use Module\Core\Models\Content;
use Module\Core\Models\User;

/**
 * Class AdminIndexController
 * @package Admin\Controllers
 */
class AdminIndexController extends AdminController
{
    /**
     * Display Admin Dashboard
     */
    public function indexAction()
    {
        $this->setTitle('Dashboard');
        $this->view->setVars([
            "os" => php_uname(),
            "php" => phpversion(),
            'countArticles' => Content::count(),
            'countUsers' => User::count(),
            "config" => $this->config
        ]);
    }
}