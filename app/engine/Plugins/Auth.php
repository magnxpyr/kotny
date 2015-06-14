<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Plugins;

use Core\Models\UserAuthTokens;
use Core\Models\User;
use Engine\Plugins\Connectors\GoogleConnector;
use Engine\Plugins\Connectors\FacebookConnector;
use Engine\Utils;
use Phalcon\Mvc\User\Component;

/**
 * Manages Authentication/Identity Management
 *
 * Class Auth
 * @package Engine\Plugins
 */
class Auth extends Component
{
    /**
     * Cookie settings from config
     * @var \stdClass
     */
    private $cookie;

    /**
     * Initialize Auth
     */
    public function __construct()
    {
        $this->cookie = $this->config->app->cookie;
    }


    /**
     * Checks the user credentials
     *
     * @param $credentials
     * @throws \Exception
     */
    public function check($credentials)
    {
        $user = User::findFirstByUsername($credentials['username']);
        if (!$user) {
        //    $this->registerUserThrottling(null);
            throw new \Exception($this->t->_('Username or password is invalid'));
        }
        if (!$this->security->checkHash($credentials['password'], $user->getPassword())) {
        //    $this->registerUserThrottling($user->getId());
            throw new \Exception($this->t->_('Username or password is invalid'));
        }
        $this->checkUserFlags($user);
    //    $this->saveSuccessLogin($user);
        if (isset($credentials['remember'])) {
            $this->setRememberMe($user);
        }
        $this->setSession($user);
    }

    /**
     * Set user session
     *
     * @param object $user
     */
    private function setSession($user)
    {
        $this->session->set('auth', [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
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
                return $this->loginWithRememberMe(false);
            }
        } else {
            if ($form->isValid($this->request->getPost())) {
                $this->check([
                    'username' => $this->request->getPost('username', 'alphanum'),
                    'password' => $this->request->getPost('password', 'string'),
                    'remember' => $this->request->getPost('remember', 'int')
                ]);
                /*
                return $this->response->redirect([
                    'for' => 'user-home',
                    'module' => 'core',
                    'controller' => 'user',
                    'action' => 'index'
                ]);
                */
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
     *
     * @param boolean $redirect
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe($redirect = true)
    {
        $cookie = explode(':', $this->cookies->get($this->cookie->name)->getValue());
    //    $auth = UserAuthTokens::findFirstBySelector($cookie[0]);
        $auth = UserAuthTokens::findFirstBySelector($cookie[0])->load('User');
        if ($auth) {
            // fist check cookie valid and then look for user
            if (Utils::hash_equals($auth->getToken(), hash('sha256', trim($cookie[1])))) {
                if ($auth->getExpires() > time() ) {
                    // Get user details to set a new session
                    $this->checkUserFlags($auth->user);
                    $this->setSession($auth->user);
                    $this->setRememberMe(null, $auth);
                    // $this->saveSuccessLogin($user);
                    if ($redirect) {
                        return $this->response->redirect();
                    }
                    return;
                }
            }
            $auth->delete();
        }
        $this->cookies->get($this->cookie->name)->delete();
        return $this->response->redirect();
    }

    /**
     * Creates the remember me environment settings, the related cookies and generating tokens
     *
     * @param \Core\Models\User $user
     * @param \Core\Models\UserAuthTokens $remember
     */
    public function setRememberMe($user = null, $remember = null)
    {
        $selector = Utils::generateToken(8);
        $token = Utils::generateToken();
        if($remember === null) {
            $remember = new UserAuthTokens();
            $remember->setUserId($user->getId());
        }
        $remember->setSelector($selector);
        $remember->setToken(hash('sha256', $token));
        $remember->setExpires($this->cookie->expire);
        if ($remember->save()) {
            $this->cookies->set(
                $this->cookie->name,
                "$selector:$token",
                $this->cookie->expire,
                $this->cookie->path,
                $this->cookie->secure,
                $this->cookie->domain,
                true);
        }
    }

    /**
     * Login with Facebook account
     *
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function loginWithFacebook()
    {
        try {
            $facebook = new FacebookConnector();
            $facebookUser = $facebook->getUser();
            if (!$facebookUser) {
                $scope = [
                //    'scope' => 'email,user_birthday,user_location'
                    'scope' => 'email'
                ];
                return $this->response->redirect($facebook->getLoginUrl($scope), true);
            }

            return $this->authenticateOrCreateFacebookUser($facebookUser);
        } catch (\Exception $e) {
            $facebookUser = null;
            $this->flashSession->error($e->getMessage());
        }
    }

    /**
     * Authenticate or create a user with a Facebook account
     *
     * @param $facebookUser
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @throws \Exception
     */
    protected function authenticateOrCreateFacebookUser($facebookUser)
    {
        $email = isset($facebookUser['email']) ? $facebookUser['email'] : Utils::generateToken(8) . '@mg.com';
        $user = User::findFirst("email='$email' OR facebook_id='" . $facebookUser['id'] . "'");
        if ($user) {
            $this->checkUserFlags($user);
            $this->setSession($user);
            if (!$user->getFacebookId()) {
                $user->setFacebookId($facebookUser['id']);
                $user->setFacebookName($facebookUser['name']);
                $user->setFacebookData(serialize($facebookUser));
                $user->save();
            }
        //    $this->saveSuccessLogin($user);
        //    return $this->response->redirect();
        } else {
            $user = $this->newUser()
                ->setEmail($email)
                ->setFacebookId($facebookUser['id'])
                ->setFacebookName($facebookUser['name'])
                ->setFacebookData(serialize($facebookUser));
            return $this->createUser($user);
        }
    }

    /**
     * Authenticate or create a user with a Google Plus account
     *
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @throws \Exception
     */
    public function loginWithGoogle()
    {
        $pupRedirect = '';
        $config['redirect_uri'] = $this->url->get('user/login-with-google');
        $google = new GoogleConnector($config);
        $response = $google->connect();
        if ($response['status'] == 0) {
            return $this->response->redirect();
        }
        $gplusId = $response['userinfo']['id'];
        $email = $response['userinfo']['email'];
        $name = $response['userinfo']['name'];
        $user = User::findFirst("gplus_id='$gplusId' OR email = '$email'");
        if ($user) {
            $this->checkUserFlags($user);
            $this->setSession($user);
            if (!$user->getGplusId()) {
                $user->setGplusId($gplusId);
                $user->setGplusName($name);
                $user->setGplusData(serialize($response['userinfo']));
                $user->update();
            }
        //    $this->saveSuccessLogin($user);
            return $this->response->redirect($pupRedirect->success);
        } else {
            $user = $this->newUser()
                ->setEmail($email)
                ->setGplusId($gplusId)
                ->setGplusName($name)
                ->setGplusData(serialize($response['userinfo']));
            return $this->createUser($user);
        }
    }

    /**
     * New user
     *
     * @return \Core\Models\User
     */
    protected function newUser()
    {
        $user = new User();
        $user->setUsername(Utils::generateToken(16));
        $user->setRoleId(1);
        $user->setStatus(1);
        $user->setCreatedAt(time());
        $user->setPassword($this->security->hash(Utils::generateToken(8)));
        return $user;
    }

    /**
     * Create (save) new user to DB
     *
     * @param \Core\Models\User $user
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @throws \Exception
     */
    protected function createUser($user)
    {
        if ($user->save()) {
            echo 'save';
            // success
            $this->setSession($user);
        //    $this->saveSuccessLogin($user);
        //    return $this->response->redirect();
        } else {
            echo 'not save';
            // failure
            foreach ($user->getMessages() as $message) {
                $this->flashSession->error($message->getMessage());
            }
            return $this->response->redirect();
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @throws \Exception
     * @param \Core\Models\User $user
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
     * Reduces the effectiveness of brute force attacks
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
        $attempts = UserFailedLogins::count([
            'ip_address = ?0 AND attempted >= ?1',
            'bind' => [
                $this->request->getClientAddress(),
                time() - 3600 * 6
            ]
        ]);
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
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has($this->cookie->name);
    }

    /**
     * Check if the user is signed in
     *
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
     *
     * @param \Core\Models\User $user
     * @throws \Exception
     */
    public function checkUserFlags($user)
    {
        if ($user->getStatus() == 0) {
            throw new \Exception($this->t->_('Your account is inactive'));
        }
        if ($user->getStatus() == 2) {
            throw new \Exception($this->t->_('Your account is suspended'));
        }
        if ($user->getStatus() == 3) {
            throw new \Exception($this->t->_('Your account is banned'));
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
        $identity = $this->session->get('auth');
        return isset($identity['username']) ? $identity['username'] : false;
    }

    /**
     * Returns the id of the user
     *
     * @return string
     */
    public function getUserId()
    {
        $identity = $this->session->get('auth');
        return isset($identity['id']) ? $identity['id'] : false;
    }

    /**
     * Removes the user identity information from session, cookies and db
     */
    public function remove()
    {
        $fbAppId = ''; //fb id
        if ($this->cookies->has($this->cookie->name)) {
            $cookie = explode(':', $this->cookies->get($this->cookie->name));
            UserAuthTokens::findFirstBySelector($cookie[0])->delete();
            $this->cookies->delete($this->cookie->name);
        }

        $this->session->remove('auth');
        $this->session->remove('fb_'.$fbAppId.'_code');
        $this->session->remove('fb_'.$fbAppId.'_access_token');
        $this->session->remove('fb_'.$fbAppId.'_user_id');
        $this->session->remove('googleToken');
        $this->session->remove('linkedIn_token');
        $this->session->remove('linkedIn_token_expires_on');
    }

    /**
     * Auths the user by his/her id
     * @param int $id
     * @throws \Exception
     * @return boolean
     */
    public function authUserById($id)
    {
        $user = User::findFirstById($id);
        if ($user == false) {
            throw new \Exception('The user does not exist');
        }
        $this->checkUserFlags($user);
        $this->setSession($user);
        return true;
    }

    /**
     * Get the entity related to user in the active identity
     * @throws \Exception
     * @return \Core\Models\User
     */
    public function getUser()
    {
        $identity = $this->session->get('auth');
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