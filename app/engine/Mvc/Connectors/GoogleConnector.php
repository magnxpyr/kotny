<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc\Connectors;

use Engine\Meta;
use Engine\Utils;
use Phalcon\Di\Injectable;

/**
 * Class GoogleConnector
 * @package Engine\Mvc\Connectors
 */
class GoogleConnector extends Injectable
{
    use Meta;
    
    /**
     * API for the needed information
     * @var array
     */
    private $scopes = [
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile'
    ];

    /**
     * Connect to Google
     * @return array
     */
    public function connect()
    {
        $client = $this->getClient();
        /*
        if ($this->request->get('code')) {
            $client->authenticate($this->request->get('code'));
            $this->session->set('googleToken', $client->getAccessToken());
            return ['status' => 0];
        }
        if ($this->session->has('googleToken')) {
            $client->setAccessToken($this->session->get('googleToken'));
        }
        if ($client->getAccessToken()) {
            $service  = new \Google_Service_Oauth2($client);
            $this->session->set('googleToken', $client->getAccessToken());
            return ['status' => 1, 'userinfo' => $service->userinfo->get()];
        } else {
            $authUrl = $client->createAuthUrl();
            return ['status' => 2, 'redirect' => $authUrl];
        }
        */
        if ($this->request->get('code')) {
            $client->authenticate($this->request->get('code'));
            $this->session->set('googleToken', $client->getAccessToken());
            $service  = new \Google_Service_Oauth2($client);
            return ['status' => 1, 'userinfo' => $service->userinfo->get()];
        } else {
            $authUrl = $client->createAuthUrl();
            return ['status' => 0, 'redirect' => $authUrl];
        }
    }

    /**
     * Get client
     *
     * @return \Google_Client
     */
    public function getClient()
    {
        $client = new \Google_Client();
        $client->setScopes($this->scopes);
        $client->setApplicationName($this->config->siteName);
        $client->setClientId($this->config->gOAuthId);
        $client->setClientSecret($this->config->gOAuthSecret);
        $client->setRedirectUri($this->helper->getUri('user/login-with-google'));
        $client->setDeveloperKey($this->config->gOAuthKey);

        return $client;
    }
}