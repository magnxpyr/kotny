<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\TopContent\Controllers;

use Widget\TopContent\Forms\AdminIndexForm;

/**
 * Class Controller
 * @package Widget\TopContent\Controllers
 */
class AdminController extends \Engine\Widget\Controller
{
    public function indexAction()
    {
        $form = new AdminIndexForm();
        $params = (object) $this->getParams();
        if (!isset($params->limit)) {
            $params->widgetLimit = 10;
        }

        $form->setEntity($params);

        $this->viewWidget->setVar('form', $form);
    }
}