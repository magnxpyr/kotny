<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Plugins\Connectors;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;
use Phalcon\Di\Injectable;

/**
 * Class FacebookConnector
 * @package Phalcon\UserPlugin\Connectors
 */
class FacebookConnector extends Injectable
{
    private $fbSession;
    private $helper;
    private $loginURL;

    /**
     * Setup connector
     */
    public function __construct()
    {
        $protocol  = 'http://';
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
            $protocol = 'https://';
        }
        $this->loginURL = $protocol . $_SERVER['HTTP_HOST'] . $this->url->get('/user/login-with-facebook');
        FacebookSession::setDefaultApplication(
            $this->config->connectors->facebook->appId,
            $this->config->connectors->facebook->secret
        );
    }

    /**
     * @param array $scope
     * @return string
     */
    public function getLoginUrl($scope = [])
    {
        $this->helper = new FacebookRedirectLoginHelper($this->loginURL);
        return $this->helper->getLoginUrl($scope);
    }

    /**
     * Get facebook user details
     * @return boolean
     */
    public function getUser()
    {
        try {
            $this->helper  = new FacebookRedirectLoginHelper($this->loginURL);
            $this->fbSession = $this->helper->getSessionFromRedirect();
        } catch (FacebookRequestException $e) {
            $this->flashSession->error($e->getMessage());
        } catch (\Exception $e) {
            $this->flashSession->error($e->getMessage());
        }
        if ($this->fbSession) {
            $request  = new FacebookRequest($this->fbSession, 'GET', '/me');
            $response = $request->execute();
            $fb_user  = $response->getGraphObject()->asArray();
            return $fb_user;
        }
        return false;
    }
}