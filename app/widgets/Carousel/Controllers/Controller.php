<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\Carousel\Controllers;

/**
 * Class Controller
 * @package Widget\Carousel\Controllers
 */
class Controller extends \Engine\Widget\Controller
{

    public function indexAction()
    {
        $this->viewWidget->setVar('images', $this->getParam("_images"));
    }
}