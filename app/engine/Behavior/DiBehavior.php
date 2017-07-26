<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Behavior;

use Engine\Meta;
use Phalcon\DI;

/**
 * Dependency container trait.
 * @package Engine\Behavior
 */
trait DiBehavior
{
    use Meta;

    /**
     * Dependency injection container.
     * @var DI|Meta
     */
    private $di;
    /**
     * @var \Engine\Acl\MemoryBase
     */
    private $acl;
    /**
     * @var \Phalcon\Logger\Adapter
     */
    private $logger;
    /**
     * @var \Engine\Mvc\Helper
     */
    protected $helper;
    /**
     * @var \Phalcon\Db\Adapter\Pdo
     */
    private $db;
    /**
     * @var \Phalcon\Cache\Backend
     */
    private $cache;
    /**
     * @var \Phalcon\Config
     */
    private $config;

    /**
     * Set DI.
     * @param \Phalcon\DiInterface $di
     * @return void
     */
    public function setDI($di)
    {
        $this->di = $di;
    }

    /**
     * Set DI.
     * @return DI|Meta
     */
    public function getDI()
    {
        if ($this->di == null) {
            $di = Di::getDefault();
            $this->setDI($di);

            if ($di->has('acl')) {
                $this->acl = $di->get('acl');
            }
            $this->logger = $di->get('logger');
            $this->helper = $di->get('helper');
            $this->db = $di->get('db');
            $this->cache = $di->get('cache');
            $this->config = $di->get('config');
        }
        return $this->di;
    }
}