<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;
use Engine\Meta;
use Phalcon\Assets\Filters\Cssmin;
use Phalcon\Assets\Filters\Jsmin;

/**
 * Base Controller
 * @package   Engine
 */
abstract class Controller extends \Phalcon\Mvc\Controller
{
    use Meta;
    
    /**
     * Initializes the controller
     * @return void
     */
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->setMetaDefaults();
            $this->setupAssets();
        }
    }

    protected function setMetaDefaults()
    {
        $this->view->setVar('token', $this->tokenManager->getToken());
        $this->view->setVar('title', '');
        $this->view->setVar('metaShowAuthor', $this->config->metaAuthor);
        $this->view->setVar('metaDescription', $this->config->metaDesc);
        $this->view->setVar('metaAuthor', '');
        $this->view->setVar('metaKeywords', $this->config->metaKeys);
        $this->view->setVar('metaContentRights', $this->config->metaRights);
        $this->view->setVar('metaRobots', $this->config->metaRobots);
    }

    /**
     * Set page title
     * @param string $title
     */
    protected function setTitle($title, $headerOnly = false)
    {
        if($title === null) {
            return;
        }
        switch($this->config->siteNameLocation) {
            case 0:
                $this->tag->setTitle($title);
                break;
            case 1:
                $this->tag->setTitle($this->config->siteName . ' | ' . $title);
                break;
            case 2:
                $this->tag->setTitle($title . '|' . $this->config->siteName);
                break;
        }
        if(!$headerOnly) {
            $this->view->setVar('title', "<h1>$title</h1>");
        }
    }

    /**
     * Flash error messages
     * @param $model
     */
    protected function flashErrors($model)
    {
        foreach ($model->getMessages() as $message) {
            $this->flash->error((string) $message);
        }
    }

    /**
     * Setup assets
     * @return void
     */
    protected function setupAssets()
    {
        $this->assets->collection('header-css');
        $this->assets->collection('footer-js');
    }

    /**
     * Json encode ajax request
     * @param $response
     */
    public function returnJSON($response)
    {
        $this->view->disable();
        $this->response->setJsonContent($response);
        $this->response->send();
    }
}