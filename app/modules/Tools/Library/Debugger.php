<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Tools\Library;

use Phalcon\Di\InjectionAwareInterface;

class Debugger implements InjectionAwareInterface {

    /**
     * @var \Phalcon\DiInterface $di
     */
    private $_di;

    private $_profiler;
    private $startTime;
    private $endTime;
    private $queryCount = 0;

    public function __construct() {

    }

    /**
     * Sets the dependency injector
     *
     * @param \Phalcon\DiInterface $di
     */
    public function setDI(\Phalcon\DiInterface $di) {
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
        return $this->_viewsRendered;
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
}