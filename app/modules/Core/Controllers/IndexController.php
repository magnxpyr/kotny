<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Engine\Mvc\Controller;
use Phalcon\Mvc\View;

/**
 * Class IndexController
 * @package Core\Controllers
 */
class IndexController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['*'],
                        'roles' => ['*']
                    ]
                ]
            ]
        ];
    }

    public function indexAction()
    {

    }
}

