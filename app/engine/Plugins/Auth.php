<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Plugins;

use Core\Models\AuthTokens;
use Core\Models\User;
use Engine\Utils;
use Phalcon\Mvc\User\Component;

/**
 * Manages Authentication/Identity Management
 * Class Auth
 * @package Engine\Plugins
 */
class Auth extends Component
{
    /**
     * Cookie name
     * @var string
     */
    private $cookieName = 'mgRm';

    /**
     * Cookie expire time
     * @var string
     */
    private $cookieExpire;

    public function __construct()
    {
        $this->cookieExpire = time() + 86400 * 30;
    }


    /**
     * Checks the user credentials
     * @param $credentials
     * @throws \Exception
     */
    public function check($credentials)
    {
        $user = User::findFirstByUsername($credentials['username']);
        if (!$user) {
        //    $this->registerUserThrottling(null);
            throw new \Exception($this->t['Username or password is invalid']);
        }
        if (!$this->security->checkHash($credentials['password'], $user->getPassword())) {
        //    $this->registerUserThrottling($user->getId());
            throw new \Exception($this->t['Username or password is invalid']);
        }
    //    $this->checkUserFlags($user);
    //    $this->saveSuccessLogin($user);
        if (isset($credentials['remember'])) {
            $this->setRememberMe($user);
        }
        $this->setSession($user);
    }

    /**
     * Set user session
     * @param object $user
     */
    private function setSession($user)
    {
        $this->session->set('auth', [
            'id'    => $user->getId(),
            'username'  => $user->getUsername(),
            'email' => $user->getEmail(),
        ]);
    }

    /**
     * Login user - normal way
     *
     * @param  \Core\Forms\LoginForm $form
     * @return \Phalcon\Http\ResponseInterface
     */
    public function login($form)
    {
        if (!$this->request->isPost()) {
            if ($this->hasRememberMe()) {
                return $this->loginWithRememberMe();
            }
        } else {
            if ($form->isValid($this->request->getPost())) {
                $this->check(array(
                    'username' => $this->request->getPost('email', 'alphanum'),
                    'password' => $this->request->getPost('password'),
                    'remember' => $this->request->getPost('remember')
                ));
                return $this->response->redirect();
            } else {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message->getMessage());
                }
            }
        }
        return false;
    }

    /**
     * Logs on using the information in the cookies
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe($redirect = true)
    {
        $cookie = explode(':', $this->cookies->get($this->cookieName)->getValue());
        $auth = AuthTokens::findFirstBySelector($cookie[0]);
        if ($auth) {
            // fist check cookie valid and then look for user
            if (hash_equals($auth->getToken(), hash('sha256', $cookie[1]))) {
                if ($auth->getExpires() > $this->cookieExpire ) {
                    // Get user details to set a new session
                    $user = User::findFirstById($auth->getUserId);
                    $this->checkUserFlags($user);
                    $this->setSession($user);
                    $this->updateRememberMe($user);
                    // $this->saveSuccessLogin($user);
                    if (true === $redirect) {
                        return $this->response->redirect();
                    }
                    return;
                }
            }
        }
        $this->cookies->get($this->cookieName)->delete();
        return $this->response->redirect();
    }

    /**
     * Login with facebook account
     */
    public function loginWithFacebook()
    {
        $di           = $this->getDI();
        $facebook     = new FacebookConnector($di);
        $facebookUser = $facebook->getUser();
        if (!$facebookUser) {
            $scope = [
                'scope' => 'email,user_birthday,user_location'
            ];
            return $this->response->redirect($facebook->getLoginUrl($scope), true);
        }
        try {
            return $this->authenticateOrCreateFacebookUser($facebookUser);
        } catch (\FacebookApiException $e) {
            $di->logger->begin();
            $di->logger->error($e->getMessage());
            $di->logger->commit();
            $facebookUser = null;
        }
    }
    /**
     * Authenitcate or create a user with a Facebook account
     *
     * @param array $facebookUser
     */
    protected function authenticateOrCreateFacebookUser($facebookUser)
    {
        $pupRedirect = $this->di->get('config')->pup->redirect;
        $email       = isset($facebookUser['email']) ? $facebookUser['email'] : 'a@a.com';
        $user        = User::findFirst(" email='$email' OR facebook_id='".$facebookUser['id']."' ");
        if ($user) {
            $this->checkUserFlags($user);
            $this->setIdentity($user);
            if (!$user->getFacebookId()) {
                $user->setFacebookId($facebookUser['id']);
                $user->setFacebookName($facebookUser['name']);
                $user->setFacebookData(serialize($facebookUser));
                $user->update();
            }
            $this->saveSuccessLogin($user);
            return $this->response->redirect($pupRedirect->success);
        } else {
            $password = $this->generatePassword();
            $user = $this->newUser()
                ->setName($facebookUser['name'])
                ->setEmail($email)
                ->setPassword($di->get('security')->hash($password))
                ->setFacebookId($facebookUser['id'])
                ->setFacebookName($facebookUser['name'])
                ->setFacebookData(serialize($facebookUser));
            return $this->createUser($user);
        }
    }
    /**
     * Login with LinkedIn account
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function loginWithLinkedIn()
    {
        $di = $this->getDI();
        $config = $di->get('config')->pup->connectors->linkedIn->toArray();
        $config['callback_url'] = $config['callback_url'].'user/loginWithLinkedIn';
        $li = new LinkedInConnector($config);
        $token = $this->session->get('linkedIn_token');
        $token_expires = $this->session->get('linkedIn_token_expires_on', 0);
        if ($token && $token_expires > time()) {
            $li->setAccessToken($this->session->get('linkedIn_token'));
            $email = $li->get('/people/~/email-address');
            $info  = $li->get('/people/~');
            return $this->authenticateOrCreateLinkedInUser($email, $info);
        } else { // If token is not set
            if ($this->request->get('code')) {
                $token = $li->getAccessToken($this->request->get('code'));
                $token_expires = $li->getAccessTokenExpiration();
                $this->session->set('linkedIn_token', $token);
                $this->session->set('linkedIn_token_expires_on', time() + $token_expires);
            }
        }
        $state = uniqid();
        $url   = $li->getLoginUrl([
            LinkedInConnector::SCOPE_BASIC_PROFILE,
            LinkedInConnector::SCOPE_EMAIL_ADDRESS
        ], $state);
        return $this->response->redirect($url, true);
    }
    protected function authenticateOrCreateLinkedInUser($email, $info)
    {
        $pupRedirect = $di->get('config')->pup->redirect;
        preg_match('#id=\d+#', $info['siteStandardProfileRequest']['url'], $matches);
        $linkedInId  = str_replace("id=", "", $matches[0]);
        $user        = User::findFirst("email='$email' OR linkedin_id='$linkedInId'");
        if ($user) {
            $this->checkUserFlags($user);
            $this->setIdentity($user);
            $this->saveSuccessLogin($user);
            if (!$user->getLinkedinId()) {
                $user->setLinkedinId($linkedInId);
                $user->setLinkedinName($info['firstName'].' '.$info['lastName']);
                $user->update();
            }
            return $this->response->redirect($pupRedirect->success);
        } else {
            $password = $this->generatePassword();
            $user = $this->newUser()
                ->setName($info['firstName'].' '.$info['lastName'])
                ->setEmail($email)
                ->setPassword($di->get('security')->hash($password))
                ->setLinkedinId($linkedInId)
                ->setLinkedinName($info['firstName'].' '.$info['lastName'])
                ->setLinkedinData(json_encode($info));
            return $this->createUser($user);
        }
    }
    /**
     * Login with Twitter account
     */
    public function loginWithTwitter()
    {
        $di          = $this->getDI();
        $pupRedirect = $di->get('config')->pup->redirect;
        $oauth       = $this->session->get('twitterOauth');
        $config      = $di->get('config')->pup->connectors->twitter->toArray();
        $config      = array_merge($config, array('token' => $oauth['token'], 'secret' => $oauth['secret']));
        $twitter = new TwitterConnector($config, $di);
        if (!$this->request->get('oauth_token')) {
            return $this->response->redirect($twitter->request_token(), true);
        }
        $twitter->access_token();
        $code = $twitter->user_request(array(
            'url' => $twitter->url('1.1/account/verify_credentials')
        ));
        if ($code == 200) {
            $data = json_decode($twitter->response['response'], true);
            if ($data['screen_name']) {
                $code = $twitter->user_request(array(
                    'url' => $twitter->url('1.1/users/show'),
                    'params' => array(
                        'screen_name' => $data['screen_name']
                    )
                ));
                if ($code == 200) {
                    $response = json_decode($twitter->response['response'], true);
                    $twitterId = $response['id'];
                    $user = User::findFirst("twitter_id='$twitterId'");
                    if ($user) {
                        $this->checkUserFlags($user);
                        $this->setIdentity($user);
                        $this->saveSuccessLogin($user);
                        return $this->response->redirect($pupRedirect->success);
                    } else {
                        $password = $this->generatePassword();
                        $email    = $response['screen_name'].rand(100000,999999).'@domain.tld'; // Twitter does not prived user's email
                        $user = $this->newUser()
                            ->setName($response['name'])
                            ->setEmail($email)
                            ->setPassword($di->get('security')->hash($password))
                            ->setTwitterId($response['id'])
                            ->setTwitterName($response['name'])
                            ->setTwitterData(json_encode($response));
                        $this->flashSession->notice('Because Twitter does not provide an email address, we had randomly generated one: '.$email);
                        return $this->createUser($user);
                    }
                }
            }
        } else {
            $di->get('logger')->begin();
            $di->get('logger')->error(json_encode($twitter->response));
            $di->get('logger')->commit();
        }
    }
    public function loginWithGoogle()
    {
        $di       = $this->getDI();
        $config   = $di->get('config')->pup->connectors->google->toArray();
        $pupRedirect            = $di->get('config')->pup->redirect;
        $config['redirect_uri'] = $config['redirect_uri'].'user/loginWithGoogle';
        $google = new GoogleConnector($config);
        $response = $google->connect($di);
        if ($response['status'] == 0) {
            return $this->response->redirect($response['redirect'], true);
        }
        $gplusId = $response['userinfo']['id'];
        $email   = $response['userinfo']['email'];
        $name    = $response['userinfo']['name'];
        $user    = User::findFirst("gplus_id='$gplusId' OR email = '$email'");
        if ($user) {
            $this->checkUserFlags($user);
            $this->setIdentity($user);
            if (!$user->getGplusId()) {
                $user->setGplusId($gplusId);
                $user->setGplusName($name);
                $user->setGplusData(serialize($response['userinfo']));
                $user->update();
            }
            $this->saveSuccessLogin($user);
            return $this->response->redirect($pupRedirect->success);
        } else {
            $password = $this->generatePassword();
            $user = $this->newUser()
                ->setName($name)
                ->setEmail($email)
                ->setPassword($di->get('security')->hash($password))
                ->setGplusId($gplusId)
                ->setGplusName($name)
                ->setGplusData(serialize($response['userinfo']));
            return $this->createUser($user);
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Phalcon\UserPlugin\Models\User\User $user
     */
    public function saveSuccessLogin($user)
    {
        $successLogin = new UserSuccessLogins();
        $successLogin->setUserId($user->getId());
        $successLogin->setIpAddress($this->request->getClientAddress());
        $successLogin->setUserAgent($this->request->getUserAgent());
        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
            throw new \Exception($messages[0]);
        }
    }
    /**
     * Implements login throttling
     * Reduces the efectiveness of brute force attacks
     *
     * @param int $user_id
     */
    public function registerUserThrottling($user_id)
    {
        $failedLogin = new UserFailedLogins();
        $failedLogin->setUserId($user_id == null ? new \Phalcon\Db\RawValue('NULL') : $user_id);
        $failedLogin->setIpAddress($this->request->getClientAddress());
        $failedLogin->setAttempted(time());
        $failedLogin->save();
        $attempts = UserFailedLogins::count(array(
            'ip_address = ?0 AND attempted >= ?1',
            'bind' => array(
                $this->request->getClientAddress(),
                time() - 3600 * 6
            )
        ));
        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     * @param \Core\Models\User $user
     */
    public function setRememberMe($user)
    {
        $selector = Utils::generateToken(8);
        $token = Utils::generateToken();
        $remember = new AuthTokens();
        $remember->setUserId($user->getId());
        $remember->setToken(hash('sha256', $token));
        $remember->setExpires($this->cookieExpire);
        if ($remember->save()) {
            $this->cookies->set($this->cookieName, "$selector:$token", $this->cookieExpire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has($this->cookieName);
    }

    /**
     * Check if the user is signed in
     * @return boolean
     */
    public function isUserSignedIn()
    {
        $auth = $this->getSession();
        if (is_array($auth)) {
            if (isset($auth['id'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if the user is inactive/suspended/banned
     * @param \Core\Models\User $user
     * @throws \Exception
     */
    public function checkUserFlags($user)
    {
        if ($user->getStatus() === 0) {
            throw new \Exception($this->t['Your account is inactive']);
        }
        if ($user->getStatus() === 2) {
            throw new \Exception($this->t['Your account is suspended']);
        }
        if ($user->getStatus() === 3) {
            throw new \Exception($this->t['Your account is banned']);
        }
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getSession()
    {
        return $this->session->get('auth');
    }

    /**
     * Returns the name of the user
     *
     * @return string
     */
    public function getUserName()
    {
        $identity = $this->session->get('auth-identity');
        return isset($identity['name']) ? $identity['name'] : false;
    }

    /**
     * Returns the id of the user
     *
     * @return string
     */
    public function getUserId()
    {
        $identity = $this->session->get('auth-identity');
        return isset($identity['id']) ? $identity['id'] : false;
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        $fbAppId = ''; //fb id
        if ($this->cookies->has($this->cookieName)) {
            $this->cookies->get($this->cookieName)->delete();
        }
        $this->session->remove('auth-identity');
        $this->session->remove('fb_'.$fbAppId.'_code');
        $this->session->remove('fb_'.$fbAppId.'_access_token');
        $this->session->remove('fb_'.$fbAppId.'_user_id');
        $this->session->remove('googleToken');
        $this->session->remove('linkedIn_token');
        $this->session->remove('linkedIn_token_expires_on');
    }
    /**
     * Auths the user by his/her id
     *
     * @param int $id
     */
    public function authUserById($id)
    {
        $user = User::findFirstById($id);
        if ($user == false) {
            throw new \Exception('The user does not exist');
        }
        $this->checkUserFlags($user);
        $this->setIdentity($user);
        return true;
    }
    /**
     * Get the entity related to user in the active identity
     *
     * @return Phalcon\UserPlugin\Models\User\User
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (!isset($identity['id'])) {
            return false;
        }
        $user = User::findFirstById($identity['id']);
        if ($user == false) {
            throw new \Exception('The user does not exist');
        }
        return $user;
    }
}