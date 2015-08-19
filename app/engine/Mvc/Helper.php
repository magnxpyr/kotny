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
    private $userRoles = [
        0 => 'All',
        1 => 'Guest',
        2 => 'User',
        3 => 'Admin'
    ];

    private $userStatuses = [
        1 => 'Active',
        0 => 'Inactive',
        2 => 'Blocked'
    ];

    private $articleStatuses = [
        1 => 'Published',
        0 => 'Unpublished',
        2 => 'Trashed'
    ];

    /**
     * Get user role by id
     * @param $id
     * @return mixed
     */
    public function getUserRole($id)
    {
        return $this->userRoles[$id];
    }

    /**
     * Get possible permission roles for users
     * @return array
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Get user status by id
     * @param $id
     * @return mixed
     */
    public function getUserStatus($id)
    {
        return $this->userStatuses[$id];
    }

    /**
     * Get possible statuses for users
     * @return array
     */
    public function getUserStatuses()
    {
        return $this->userStatuses;
    }

    /**
     * Get article status by id
     * @param $id
     * @return mixed
     */
    public function getArticleStatus($id)
    {
        return $this->articleStatuses[$id];
    }

    /**
     * Get possible statuses for articles
     * @return array
     */
    public function getArticleStatuses()
    {
        return $this->articleStatuses;
    }

    /**
     * Return full url
     *
     * @param string $path
     * @param bool|true $get
     * @param string|null $params
     * @return string
     */
    public function getUri($path, $get = true, $params = null) {
        $protocol  = 'http://';
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
            $protocol = 'https://';
        }
        if($get) {
            $path = $this->url->get($path);
        }
        if($params !== null) {
            $path .= $params;
        }
        return $protocol . $_SERVER['HTTP_HOST'] . $path;
    }

    /**
     * Get url based on route name
     *
     * @param string $path
     * @param string $routeName
     * @return string
     */
    public function getRoutePath($path, $routeName = 'default-mcap')
    {
        $pathArray = explode('/', $path);

        $params = '';
        if (isset($pathArray[3])) {
            $params = $pathArray[3];
        }

        if (strpos('admin', $pathArray[1]) !== false) {
            return $this->url->get('admin/' . $path);
        }

        return $this->url->get(
            [
                'for' => $routeName,
                'module' => $pathArray[0],
                'controller' => $pathArray[1],
                'action' => $pathArray[2],
                'params' => $params
            ]
        );
    }

}