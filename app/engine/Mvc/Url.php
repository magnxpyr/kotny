<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

/**
 * Class Url
 * @package engine\Mvc
 */
class Url extends \Phalcon\Mvc\Url
{
    const HISTORY_URI = 'historyUri';

    public function setHistory()
    {
        if ($this->getDI()->getShared('request')->isAjax() || !$this->getDI()->getShared('request')->isGet()) {
            return;
        }

        $currentUri = $this->getDI()->getShared('router')->getRewriteUri();
        $session = $this->getDI()->getShared('session');
        if ($session->has(self::HISTORY_URI)) {
            $historyUri = $session->get(self::HISTORY_URI);
            if ($historyUri[0] != $currentUri) {
                $historyUri[1] = $historyUri[0];
                $historyUri[0] = $currentUri;
                $this->getDI()->getShared('session')->set(self::HISTORY_URI, $historyUri);
            }
        } else {
            $this->getDI()->getShared('session')->set(self::HISTORY_URI, [$currentUri]);
        }
    }

    public function previousUri()
    {
        $historyUri = $this->getDI()->getShared('session')->get(self::HISTORY_URI);
        return $this->getDI()->getShared('url')->get($historyUri[1]);
    }
}