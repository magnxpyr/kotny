<?php

namespace Engine\Library;

use Phalcon\Di\InjectionAwareInterface;
use Phalcon\DiInterface,
	Phalcon\Db\Profiler,
	Phalcon\Escaper,
	Phalcon\Mvc\Url,
	Phalcon\Mvc\View;

/**
 * Class DebugWidget
 * @package Engine\Library
 */
class DebugWidget implements InjectionAwareInterface {

	protected $_di;
	private $startTime;
	private $endTime;
	private $queryCount = 0;
	protected $_profiler;
	protected $_viewsRendered = array();
	protected $_serviceNames = array();

	public function __construct($di, $serviceNames = ['db' => ['db'], 'view' => ['view']]) {
		$this->_di = $di;
		$this->startTime = microtime(true);
		$this->_profiler = new Profiler();

		$eventsManager = $di->get('eventsManager');

		foreach ($di->getServices() as $service) {
			$name = $service->getName();
			foreach ($serviceNames as $eventName => $services) {
				if (in_array($name, $services)) {
					$service->setShared(true);
					if ($di->get($name)->getEventsManager() == null) {
                        $eventsManager->attach($eventName, $this);
                        $di->get($name)->setEventsManager($eventsManager);
                    } else {
                        $di->get($name)->getEventsManager()->attach($eventName, $this);
                    }
					break;
				}
			}
		}

		$this->_serviceNames = $serviceNames;
	}

    /**
     * @param \Phalcon\DiInterface $di
     */
	public function setDI(DiInterface $di) {
		$this->_di = $di;
	}

	public function getDI() {
		return $this->_di;
	}

    public function getStartTime() {
        return $this->startTime;
    }

    public function getEndTime() {
        return $this->endTime;
    }

    public function getQueryCount() {
        return $this->queryCount;
    }

    public function getProfiler() {
        return $this->_profiler;
    }

    public function getRenderedViews() {
        return $this->_viewsRendered;
    }

    /**
     * @param \Phalcon\Events\Event $event
     * @return array
     */
	public function getServices($event) {
		return $this->_serviceNames[$event];
	}

    /**
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Db\Adapter $connection
     */
	public function beforeQuery($event, $connection) {
		$this->_profiler->startProfile(
			$connection->getRealSQLStatement(),
			$connection->getSQLVariables(),
			$connection->getSQLBindTypes()
		);
	}

    /**
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Db\Adapter $connection
     */
	public function afterQuery($event, $connection) {
		$this->_profiler->stopProfile();
		$this->queryCount++;
	}

	/**
	 * Gets/Saves information about views and stores truncated viewParams.
	 *
	 * @param \Phalcon\Events\Event $event
	 * @param \Phalcon\Mvc\View $view
	 * @param \Phalcon\Http\Request\File $file
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


    /**
     * Return the content
     *
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\View $view
     * @param \Phalcon\Http\Request\File $viewFile
     */
	public function afterRender($event,$view,$viewFile)
	{
		$this->endTime = microtime(true);
		$content = $view->getContent();
		$rendered = $this->renderToolbar();
		$rendered .= "</body>";
		$content = str_replace("</body>", $rendered, $content);

		$view->setContent($content);
	}

    public function renderToolbar() {
        $view = new View();
        $viewDir = APP_PATH .'modules/Core/Views/debugWidget/';
        $view->setViewsDir($viewDir);
        $this->router = $this->_di->get('router')->getMatchedRoute();

        // set vars
        $view->debugWidget = $this;

        $content = $view->getRender('toolbar', 'index');
        return $content;
    }
}
