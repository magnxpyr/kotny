<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\Carousel\Controllers;

use Module\Core\Models\Widget;

/**
 * Class AdminController
 * @package Widget\Carousel\Controllers
 */
class AdminController extends \Engine\Widget\Controller
{
    public function indexAction()
    {
        $params = new \stdClass();
        if ($this->getParam('id')) {
            $model = Widget::findFirstById($this->getParam('id'));
            if ($model->getParams()) {
                $params = json_decode($model->getParams());
            }
        }

        $images = [];
        if (isset($params->_images)) {
            $images = $params->_images;
        }

        $this->viewWidget->setVar('images', $images);
    }
}