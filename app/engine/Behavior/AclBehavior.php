<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Behavior;

/**
 * Class AclBehavior
 * @package Engine\Behavior
 */
trait AclBehavior
{
    use DiBehavior;

    /**
     * Acl cache key.
     * @var string
     */
    protected $cacheKey = "acl_data";

    /**
     * Acl cache lifetime
     * @var int
     */
    protected $cacheExpire = 2592000; // 30 days cache.

    /**
     * Acl adapter.
     * @var \Phalcon\Acl\Adapter\Memory
     */
    protected $acl;
}