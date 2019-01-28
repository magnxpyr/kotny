<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc\Connectors;

use Engine\Meta;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Phalcon\Di\Injectable;

/**
 * Class FacebookConnector
 * @package Engine\Mvc\Connectors
 */
class FacebookConnector extends Injectable
{
    use Meta;

    private $fb;
    private $fbSession;
    private $fbHelper;

    /**
     * Setup connector
     */
    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => $this->config->fbId,
            'app_secret' => $this->config->fbSecret,
            'default_graph_version' => 'v2.8'
        ]);
        $this->fbHelper = $this->fb->getRedirectLoginHelper();
    }

    /**
     * @param array $scope
     * @return string
     */
    public function getLoginUrl($scope = [])
    {
        return $this->fbHelper->getLoginUrl($this->url->getUri('/user/login-with-facebook'), $scope);
    }

    /**
     * Get facebook user details
     * @return boolean|array
     */
    public function getUser()
    {
        try {
            $this->fbSession = $this->fbHelper->getAccessToken();
        } catch (FacebookResponseException $e) {
            $this->logger->error('Graph returned an error: ' . $e->getMessage());
            $this->flashSession->error($e->getMessage());
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            $this->logger->error('Facebook SDK returned an error: ' . $e->getMessage());
            $this->flashSession->error($e->getMessage());
        } catch (\Exception $e) {
            $this->flashSession->error($e->getMessage());
        }
        if ($this->fbSession) {
            $this->fb->setDefaultAccessToken($this->fbSession);
            try {
                $response = $this->fb->get('/me');
                $userNode = $response->getGraphUser()->asArray();
                return $userNode;
            } catch (FacebookResponseException $e) {
                // When Graph returns an error
                $this->logger->error('Graph returned an error: ' . $e->getMessage());
                $this->flashSession->error($e->getMessage());
            } catch (FacebookSDKException $e) {
                // When validation fails or other local issues
                $this->logger->error('Facebook SDK returned an error: ' . $e->getMessage());
                $this->flashSession->error($e->getMessage());
            } catch (\Exception $e) {
                $this->logger->error('Facebook API returned an error: ' . $e->getMessage());
                $this->flashSession->error($e->getMessage());
            }
        }
        return false;
    }
}