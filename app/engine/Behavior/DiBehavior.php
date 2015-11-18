<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Behavior;

use Phalcon\DI;
use Phalcon\DiInterface;

/**
 * Dependency container trait.
 * @package Engine\Behavior
 */
trait DiBehavior
{
    /**
     * Dependency injection container.
     * @var DI
     */
    private $di;

    /**
     * Create object.
     * @param DiInterface $di
     */
    public function __construct($di = null)
    {
        if ($di == null) {
            $di = DI::getDefault();
        }
        $this->setDI($di);
    }

    /**
     * Set DI.
     * @param DiInterface $di
     * @return void
     */
    public function setDI($di)
    {
        $this->di = $di;
    }

    /**
     * Get DI.
     * @return DI
     */
    public function getDI()
    {
        return $this->di;
    }
}