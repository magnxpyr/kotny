<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Plugins;

use Phalcon\Mvc\User\Plugin;

/**
 * Class Translation
 * @package Engine\Plugins
 */
class Translation extends Plugin
{
    /**
     * Check if user has access
     *
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute($event, $dispatcher)
    {
        // Get the language from session
        $lang = $this->session->get("lang");
        if (!$lang) {
            // Ask browser what is the best language
            $lang = $this->request->getBestLanguage();
        }
        // get translation directory
        $dir = "frontend";
        if ($this->helper->isBackend()) {
            $dir = "backend";
        }

        $langFile = APP_PATH . "messages/$dir/$lang.php";

        // Check if we have a translation file for the current language
        if (!file_exists($langFile)) {
            // Fallback to default
            $langFile = APP_PATH . "messages/$dir/en-US.php";
        }

        $translator = new \Phalcon\Translate\Adapter\NativeArray(['content' => require $langFile]);

        $this->di->setShared('t', $translator);
    }
}