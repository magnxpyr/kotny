<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @url         http://www.magnxpyr.com
 */

namespace Engine;

class Controller extends \Phalcon\Mvc\Controller {

    protected function _getTranslation() {
        // Get the language from session
        $language = $this->session->get("lang");
        if (!$language) {
            // Ask browser what is the best language
            $language = $this->request->getBestLanguage();
        }
        $lang_file = APP_PATH . "messages/" . $language . ".php";

        //Check if we have a translation file for that lang
        if (!file_exists($lang_file)) {
            // Fallback to default
            $lang_file = APP_PATH . "messages/en.php";
        }

        $translator = new \Phalcon\Translate\Adapter\NativeArray(require_once $lang_file);
        $this->view->setVar('t', $translator);
    }

    public function initialize() {
        //Send the value to output
        $this->_getTranslation();
    }

}