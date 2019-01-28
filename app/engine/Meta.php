<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

/**
 * Base interface for autocompletion
 * @package Engine
 * @property \Engine\Assets\Manager $assets
 * @property \Engine\Mvc\Helper $helper
 * @property \Engine\TokenManager $tokenManager
 * @property \Engine\Mvc\Auth $auth
 * @property \Engine\Acl\BaseMemory $acl
 * @property \Engine\Package\Manager $packageManager
 * @property \Engine\Widget\Widget $widget
 * @property \Engine\Mvc\View\Section $section
 * @property \Engine\Mvc\Url $url
 * @property \Engine\Mvc\View $view
 * @property \Engine\Mvc\View $viewWidget
 * @property \Engine\Mvc\Config\ConfigSample $config
 * @property \Phalcon\Mvc\View\Simple $viewSimple
 * @property \Phalcon\Mailer\Manager $mail
 * @property \Phalcon\Logger\Adapter $logger
 * @property \Phalcon\Cache\Backend $cache
 * @property \Phalcon\Translate\Adapter\NativeArray $t
 * @property \Phalcon\Mvc\Model\Manager $modelsManager
 */
trait Meta
{

}