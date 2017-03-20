<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

use Engine\Meta;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\User\Component;
use Phalcon\Text;

/**
 * Class Helper
 * @package Engine\Mvc
 */
class Helper extends Component
{
    use Meta;
    
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
    public function getUri($path, $get = true, $params = null)
    {
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
     * @param string $string
     * @return string
     */
    public function makeAlias($string)
    {
        return strtolower(str_replace(" ", "-", $string));
    }

    /**
     * Check if is an admin page
     * @return bool
     */
    public function isBackend()
    {
        $isBackend = false;
        if ($this->router->getMatchedRoute() != null && strpos($this->router->getMatchedRoute()->getName(), 'admin') !== false)
            $isBackend = true;

        return $isBackend;
    }

    /**
     * Convert array to object
     * @param $data
     * @return \stdClass
     */
    public function arrayToObject($data){
        $obj = new \stdClass();

        foreach($data as $key => $val){
            $obj->{$key} = $val;
        }

        return $obj;
    }

    /**
     * Uncamelize and replace _ with -
     * @param $str
     * @return mixed
     */
    public function uncamelize($str)
    {
        return str_replace('_', '-', Text::uncamelize($str));
    }

    /**
     * Decode html entity
     * @param $str
     * @return mixed
     */
    public function htmlDecode($str)
    {
        return html_entity_decode($str);
    }
}