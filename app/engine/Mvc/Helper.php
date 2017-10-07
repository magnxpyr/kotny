<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

use DateTime;
use Engine\Meta;
use Module\Core\Models\Content;
use Module\Core\Models\User;
use Phalcon\Mvc\User\Component;
use Phalcon\Text;

/**
 * Class Helper
 * @package Engine\Mvc
 */
class Helper extends Component
{
    use Meta;

    const DATE_FORMAT = 'd-m-Y H:i';

    private $showField = ["No", "Yes"];

    private $userStatuses = [
        User::STATUS_ACTIVE => 'Active',
        User::STATUS_INACTIVE => 'Inactive',
        User::STATUS_BLOCKED => 'Blocked'
    ];

    private $articleStatuses = [
        Content::STATUS_PUBLISHED => 'Published',
        Content::STATUS_UNPUBLISHED => 'Unpublished',
        Content::STATUS_TRASHED => 'Trashed'
    ];

    private $templateSections = [
        'menu' => 'menu', 'header' => 'header', 'heading-left' => 'heading-left', 'heading-mid' => 'heading-mid',
        'heading-right' => 'heading-right', 'banner' => 'banner', 'sidebar-left' => 'sidebar-left',
        'sidebar-right' => 'sidebar-right', 'footer' => 'footer', 'footer-left' => 'footer-left',
        'footer-mid' => 'footer-mid', 'footer-right' => 'footer-right'
    ];

    /**
     * Get status by id of a field if can be displayed
     * @param $id
     * @return mixed
     */
    public function getShowField($id)
    {
        return $this->showField[$id];
    }

    /**
     * Get statuses for a field if can be displayed
     * @return array
     */
    public function getShowFields()
    {
        return $this->showField;
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

    public function getTemplateSections()
    {
        return $this->templateSections;
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
     * Make alias from title
     * @param string $string
     * @return string
     */
    public function makeAlias($string)
    {
        return strtolower(str_replace(" ", "-", $string));
    }

    /**
     * Check if article is published
     * @param Content $model
     * @return bool
     */
    public function isContentPublished($model) {
        $time = time();
        return $model != null && $model->getPublishUp() < $time &&
            (($model->getPublishDown() != null && $model->getPublishDown() > $time) ||
                $model->getPublishDown() == null);
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

    /**
     * @param $dir
     * @return bool
     */
    public function removeDir($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            is_dir("$dir/$file") ? $this->removeDir("$dir/$file") : unlink("$dir/$file");
        }
        try {
            $removed = rmdir($dir);
        } catch (Exception $e) {
            $removed = false;
        } finally {
            if (!$removed) {
                $this->logger->error("Can't remove directory " . $dir);
            }
        }

        return $removed;
    }

    public function timestampFromDate($str)
    {
        if (is_numeric($str)) return $str;
        return !empty($str) ? DateTime::createFromFormat(self::DATE_FORMAT, $str)->getTimestamp() : null;
    }

    public function dateFromTimestamp($timestamp)
    {
        if (!is_numeric($timestamp)) return $timestamp;
        return !empty($timestamp) ? date(self::DATE_FORMAT, $timestamp) : null;
    }

    public function arrayToString($array)
    {
        if (is_array($array)) {
            return implode(", ", $array);
        }
       return null;
    }
}