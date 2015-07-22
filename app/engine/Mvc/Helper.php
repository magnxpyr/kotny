<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\User\Component;

/**
 * Class Helper
 * @package Engine\Mvc
 */
class Helper extends Component
{
    /**
     * Get possible permission roles for users
     * @return array
     */
    public function getUserRoles()
    {
        return [
            1 => 'Guest',
            2 => 'User',
            3 => 'Admin'
        ];
    }

    /**
     * Get possible statuses for users
     * @return array
     */
    public function getUserStatuses()
    {
        return ['Inactive', 'Active', 'Blocked'];
    }

    /**
     * Get possible statuses for articles
     * @return array
     */
    public function getArticleStatuses()
    {
        return ['Published', 'Unpublished', 'Trashed'];
    }

}