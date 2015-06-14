<?php
namespace Engine\Plugins\Connectors;

use Google_Client;
use Google_Auth_OAuth2 as Oauth2Service;
use Phalcon\Di\Injectable;

/**
 * Class GoogleConnector
 * @package Phalcon\UserPlugin\Connectors
 */
class GoogleConnector extends Injectable
{
    private $scopes = [
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile'
    ];
    
    public function connect()
    {
        $client = $this->getClient();
        $oauth2 = new Oauth2Service($client);
        if ($this->request->get('code')) {
            $client->authenticate($this->request->get('code'));
            $this->session->set('googleToken', $client->getAccessToken());
            $redirect = '';
            return ['status' => 0, 'redirect' => filter_var($redirect, FILTER_SANITIZE_URL)];
        }
        if ($this->session->has('googleToken')) {
            $client->setAccessToken($this->session->get('googleToken'));
        }
        if ($client->getAccessToken()) {
            $service  = new \Google_Service_Oauth2($client);
            $userinfo = $service->userinfo->get();
            $this->session->set('googleToken', $client->getAccessToken());
            return ['status' => 1, 'userinfo' => $userinfo];
        } else {
            $authUrl = $client->createAuthUrl();
            return ['status' => 0, 'redirect' => $authUrl];
        }
    }
    /**
     * Get client
     *
     * @return Google_Client
     */
    public function getClient()
    {
        $client = new Google_Client();
        $client->setScopes($this->scopes);
        $client->setApplicationName($this->config->connectors->google->app_name);
        $client->setClientId($this->config->connectors->google->client_id);
        $client->setClientSecret($this->config->connectors->google->client_secret);
        $client->setRedirectUri($this->config->connectors->google->redirect_uri);
        $client->setDeveloperKey($this->config->connectors->google->developer_key);

        return $client;
    }
}