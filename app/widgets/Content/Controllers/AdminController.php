<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\Content\Controllers;

use Widget\Content\Forms\AdminIndexForm;

/**
 * Class Controller
 * @package Widget\Content\Controllers
 */
class AdminController extends \Engine\Widget\Controller
{
    public function indexAction()
    {
        $form = new AdminIndexForm();
        $params = (object) $this->getParams();

        $form->setEntity($params);

        $this->viewWidget->setVar('form', $form);
    }
}