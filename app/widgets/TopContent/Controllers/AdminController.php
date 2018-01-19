<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\TopContent\Controllers;

use Module\Core\Models\Widget;
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
        $params = new \stdClass();
        if ($this->getParam('id')) {
            $model = Widget::findFirstById($this->getParam('id'));
            if ($model->getParams()) {
                $params = json_decode($model->getParams());
            }
        }
        if (!isset($params->limit)) {
            $params->widgetLimit = 10;
        }

        $form->setEntity($params);

        $this->viewWidget->setVar('form', $form);
    }
}