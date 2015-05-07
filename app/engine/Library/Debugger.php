<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Library;

use Phalcon\Db\Profiler;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\DiInterface;
use Phalcon\Mvc\View;

class Debugger implements InjectionAwareInterface {

    /**
     * @var \Phalcon\DiInterface $di
     */
    protected $_di;
    private $startTime;
    private $endTime;
    private $queryCount = 0;
    protected $_profiler;
    protected $_viewsRendered = array();
    protected $_serviceNames = array();

    public function __construct($di) {
        $this->_di = $di;
        $this->startTime = microtime(true);
        $this->_profiler = new Profiler();

        $serviceNames =
            array(
                'db' => array('db'),
                'dispatch' => array('dispatcher'),
                'view' => array('view')
            );

        $eventsManager = $di->get('eventsManager');

        foreach ($di->getServices() as $service) {
            $name = $service->getName();
            foreach ($serviceNames as $eventName => $services) {
                if (in_array($name, $services)) {
                    $service->setShared(true);
                    $di->get($name)->setEventsManager($eventsManager);
                    break;
                }
            }
        }
        foreach (array_keys($serviceNames) as $eventName) {
            $eventsManager->attach($eventName, $this);
        }
        $this->_serviceNames = $serviceNames;
    }

    /**
     * Sets the dependency injector
     *
     * @param \Phalcon\DiInterface $di
     */
    public function setDI(DiInterface $di) {
        $this->_di = $di;
    }

    /**
     * Returns the internal dependency injector
     *
     * @return \Phalcon\DiInterface
     */
    public function getDI() {
        return $this->_di;
    }

    /**
     * @return float
     */
    public function getStartTime() {
        return $this->startTime;
    }

    /**
     * @return float
     */
    public function getEndTime() {
        return $this->endTime;
    }

    /**
     * @return int
     */
    public function getRenderedViews() {
    //    return $this->_viewsRendered;
    }

    /**
     * @return int
     */
    public function getQueryCount() {
        return $this->queryCount;
    }

    /**
     * @return mixed
     */
    public function getProfiler() {
        return $this->_profiler;
    }

    public function beforeQuery($event, $connection) {
        $this->_profiler->startProfile(
            $connection->getRealSQLStatement(),
            $connection->getSQLVariables(),
            $connection->getSQLBindTypes()
        );
    }

    public function afterQuery($event, $connection) {
        $this->_profiler->stopProfile();
        $this->queryCount++;
    }

    /**
     * Gets/Saves information about views and stores truncated viewParams.
     *
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\View $view
     * @param mixed $file
     */
    public function beforeRenderView($event,$view,$file) {
        $params = array();
        $toView = $view->getParamsToView();
        $toView = !$toView? array() : $toView;
        foreach ($toView as $k=>$v) {
            if (is_object($v)) {
                $params[$k] = get_class($v);
            } elseif(is_array($v)) {
                $array = array();
                foreach ($v as $key=>$value) {
                    if (is_object($value)) {
                        $array[$key] = get_class($value);
                    } elseif (is_array($value)) {
                        $array[$key] = 'Array[...]';
                    } else {
                        $array[$key] = $value;
                    }
                }
                $params[$k] = $array;
            } else {
                $params[$k] = (string)$v;
            }
        }

        $this->_viewsRendered[] = array(
            'path'=>$view->getActiveRenderPath(),
            'params'=>$params,
            'controller'=>$view->getControllerName(),
            'action'=>$view->getActionName(),
        );
    }

    public function afterRender($event,$view,$viewFile) {
        $this->endTime = microtime(true);
        $content = $view->getContent();
        $rendered = $this->renderToolbar();
        $rendered .= "</body>";
        $content = str_replace("</body>", $rendered, $content);

        $view->setContent($content);
    }

    public function renderToolbar() {
        $view = new View();
        $viewDir = APP_PATH .'modules/Core/Views/';
        $view->setViewsDir($viewDir);

        // set vars
        $view->debugWidget = $this;

        $content = $view->getRender('toolbar', 'index');
        return $content;
    }
}