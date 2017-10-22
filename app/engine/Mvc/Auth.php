<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc;

use Engine\Plugins\AclHandler;
use Module\Core\Models\UserAuthTokens;
use Module\Core\Models\User;
use Engine\Meta;
use Engine\Mvc\Connectors\GoogleConnector;
use Engine\Mvc\Connectors\FacebookConnector;
use Phalcon\Mvc\User\Component;

/**
 * Manages Authentication/Identity Management
 *
 * Class Auth
 * @package Engine\Plugins
 */
class Auth extends Component
{
    use Meta;

    const THROTTLING_CACHE_KEY = "THROTTLING";
    const MAX_LOGIN_TRIES = 5;
    const THROTTLING_CACHE_LIMIT = 300;
    const WORK_FACTOR = 12;
    const SELECTOR_BYTES = 8;
    const TOKEN_BYTES = 32;
    
    /**
     * Cookie settings from config
     *
     * @var \stdClass
     */
    private $cookie;

    /**
     * Check if current user can edit articles
     *
     * @var boolean
     */
    private $isEditor;

    /**
     * Initialize Auth
     */
    public function __construct()
    {
        $this->cookie = $this->config->app->cookie;
    }

    /**
     * Login user - normal way
     *
     * @param  \Module\Core\Forms\LoginForm $form
     * @return \Phalcon\Http\ResponseInterface
     */
    public function login($form)
    {
        $this->setReturnUrl();
        if($this->isUserSignedIn()) {
            return $this->redirectReturnUrl();
        }
        if (!$this->request->isPost()) {
            if ($this->hasRememberMe()) {
                return $this->loginWithRememberMe();
            }
        } else {
            $username = $this->request->getPost('username', 'alphanum');
            $this->logger->debug("$username trying to login");
            if ($form->isValid($_POST)) {
                $this->check([
                    'username' => $username,
                    'password' => $this->request->getPost('password', 'string'),
                    'remember' => $this->request->getPost('remember', 'int')
                ]);
                $this->redirectReturnUrl();
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
     * @param bool $redirect
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe($redirect = true)
    {
        if ($this->cookies->has($this->cookie->name)) {
            $cookie = explode(':', $this->cookies->get($this->cookie->name)->getValue());
            if (count($cookie) == 2) {
                $model = $this->modelsManager->createBuilder()
                    ->columns('auth.*, user.*')
                    ->addFrom('Module\Core\Models\UserAuthTokens', 'auth')
                    ->addFrom('Module\Core\Models\User', 'user')
                    ->andWhere('auth.user_id = user.id')
                    ->andWhere('auth.selector = :id:', ['id' => $cookie[0]])
                    ->getQuery()
                    ->getSingleResult();

                if ($model) {
                    // fist check cookie valid and then look for user
                    if ($this->security->checkHash(trim($cookie[1]), $model->auth->getToken()) && $model->auth->getExpires() > time()) {
                        // Get user details to set a new session
                        $this->checkUserFlags($model->user);
                        $this->setSession($model->user);
                        $this->setRememberMe(null, $model->auth);
                         $this->saveSuccessLogin($model->user->getUsername());

                        if ($redirect) {
                            return $this->redirectReturnUrl();
                        }
                        return;
                    }
                    $model->auth->delete();
                } else {
                    $this->cookies->get($this->cookie->name)->delete();
                }
            }
        }
        if ($redirect) {
            return $this->redirectReturnUrl();
        }
    }

    /**
     * Checks the user credentials
     *
     * @param $credentials
     * @throws \Engine\Mvc\Exception
     */
    public function check($credentials)
    {
        $user = User::findFirstByUsername($credentials['username']);
        if (!$user) {
            $this->registerUserThrottling($credentials['username']);
            $this->logger->error($credentials['username'] . ": username or password is invalid");
            throw new Exception($this->t->_('Username or password is invalid'));
        }
        if (!$this->security->checkHash($credentials['password'], $user->getPassword())) {
            $this->registerUserThrottling($credentials['username']);
            $this->logger->error($credentials['username'] . ": username or password is invalid");
            throw new Exception($this->t->_('Username or password is invalid'));
        }
        $this->checkUserFlags($user);
        $this->saveSuccessLogin($user->getUsername());
        if (isset($credentials['remember'])) {
            $this->setRememberMe($user);
        }
        $this->setSession($user);
    }

    /**
     * Creates the remember me environment settings, the related cookies and generating tokens
     *
     * @param \Module\Core\Models\User $user
     * @param \Module\Core\Models\UserAuthTokens $remember
     */
    public function setRememberMe($user = null, $remember = null)
    {
        $selector = $this->security->getRandom()->hex(self::SELECTOR_BYTES);
        $token = $this->security->getRandom()->hex(self::TOKEN_BYTES);;
        if($remember === null) {
            $remember = new UserAuthTokens();
            $remember->setUserId($user->getId());
        }
        $remember->setSelector($selector);
        $remember->setToken($this->security->hash($token));
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
     * Authenticate or create a local user with a Facebook account
     *
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function loginWithFacebook()
    {
        $this->setReturnUrl();
        if($this->isUserSignedIn()) {
            return $this->redirectReturnUrl();
        }
        $facebook = new FacebookConnector();
        $facebookUser = $facebook->getUser();
        if (!$facebookUser) {
            $scope = [
            //    'scope' => 'email,user_birthday,user_location'
                'scope' => 'email'
            ];
            return $this->response->redirect($facebook->getLoginUrl($scope), true)->send();
        }

        $email = isset($facebookUser['email']) ? $facebookUser['email'] : $this->security->getRandom()->hex(self::SELECTOR_BYTES) . '@mg.com';
        $user = User::findFirst([
            'email = :email: OR facebook_id = :id:',
            'bind' => ['email' => $email, 'id' => $facebookUser['id']]
        ]);
        if ($user) {
            $this->checkUserFlags($user);
            $this->setSession($user);
            if (!$user->getFacebookId()) {
                $user->setFacebookId($facebookUser['id']);
                $user->setFacebookName($facebookUser['name']);
                $user->setFacebookData(serialize($facebookUser));
                $user->save();
            }
            $this->saveSuccessLogin($user->getUsername());
            return $this->redirectReturnUrl();
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
     * Authenticate or create a local user with a Google Plus account
     *
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @throws \Engine\Mvc\Exception
     */
    public function loginWithGoogle()
    {
        $this->setReturnUrl();
        if($this->isUserSignedIn()) {
            return $this->redirectReturnUrl();
        }
        $google = new GoogleConnector();
        $response = $google->connect();
        if ($response['status'] == 0) {
            return $this->response->redirect($response['redirect'])->send();
        }

        $gplusId = $response['userinfo']['id'];
        $email = $response['userinfo']['email'];
        $name = $response['userinfo']['name'];
        $user = User::findFirst(['gplus_id = ?1 OR email = ?2', 'bind' => [1 => $gplusId, 2 => $email]]);
        if ($user) {
            $this->checkUserFlags($user);
            $this->setSession($user);
            if (!$user->getGplusId()) {
                $user->setGplusId($gplusId);
                $user->setGplusName($name);
                $user->setGplusData(serialize($response['userinfo']));
                $user->save();
            }
            $this->saveSuccessLogin($user->getUsername());
            return $this->redirectReturnUrl();
        } else {
            $user = $this->newUser()
                ->setEmail($email)
                ->setName($name)
                ->setGplusId($gplusId)
                ->setGplusName($name)
                ->setGplusData(serialize($response['userinfo']));
            return $this->createUser($user);
        }
    }

    /**
     * New user
     *
     * @return \Module\Core\Models\User
     */
    protected function newUser()
    {
        $user = new User();
        $user->setUsername($this->security->getRandom()->hex(self::SELECTOR_BYTES));
        $user->setRoleId(2); // user
        $user->setStatus(1); // enable
        $user->setCreatedAt(time());
        $user->setPassword($this->security->hash($this->security->getRandom()->hex(self::TOKEN_BYTES)));
        return $user;
    }

    /**
     * Create new user to DB
     *
     * @param \Module\Core\Models\User $user
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @throws \Engine\Mvc\Exception
     */
    protected function createUser($user)
    {
        if ($user->save()) {
            $this->setSession($user);
            return $this->redirectReturnUrl();
        } else {
            foreach ($user->getMessages() as $message) {
                $this->flashSession->error($message->getMessage());
            }
            return $this->redirectReturnUrl();
        }
    }

    /**
     * Reset user throttling limit
     * @param string $userName
     */
    private function saveSuccessLogin($userName)
    {
        $this->cache->delete($this->getThrottlingCacheKey($userName));
        $this->logger->debug("$userName throttling level removed");
    }

    /**
     * Implements login throttling
     * Reduces the effectiveness of brute force attacks
     *
     * @param string $userName
     */
    private function registerUserThrottling($userName)
    {
        $throttlingCacheKey = $this->getThrottlingCacheKey($userName);
        $throttlingLevel = $this->cache->get($throttlingCacheKey);
        $throttlingLevel = $throttlingLevel != null ? $throttlingLevel + 1 : 1;
        $this->cache->save($throttlingCacheKey, $throttlingLevel, self::THROTTLING_CACHE_LIMIT);
        $this->logger->debug("$userName throttling level: $throttlingLevel");
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
     * @param \Module\Core\Models\User $user
     * @throws \Engine\Mvc\Exception
     */
    public function checkUserFlags($user)
    {
        if ($user->getStatus() == 0) {
            throw new Exception($this->t->_('Your account is inactive'));
        }
        if ($user->getStatus() == 2) {
            throw new Exception($this->t->_('Your account is suspended'));
        }
        if ($user->getStatus() == 3) {
            throw new Exception($this->t->_('Your account is banned'));
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
        $this->setReturnUrl();
        $fbAppId = $this->config->api->facebook->appId; //fb id
        if ($this->cookies->has($this->cookie->name)) {
            $cookie = explode(':', $this->cookies->get($this->cookie->name));
            $userAuthTokens = UserAuthTokens::findFirstBySelector($cookie[0]);
            if ($userAuthTokens) {
                $userAuthTokens->delete();
            }
            $this->cookies->delete($this->cookie->name);
        }

        $this->session->remove('auth');
        $this->session->remove('fb_'.$fbAppId.'_code');
        $this->session->remove('fb_'.$fbAppId.'_access_token');
        $this->session->remove('fb_'.$fbAppId.'_user_id');
        $this->session->remove('googleToken');
        return $this->redirectReturnUrl();
    }

    /**
     * Authenticate a user by his id
     *
     * @param int $id
     * @param \Module\Core\Models\User|null $user
     * @throws \Engine\Mvc\Exception
     * @return boolean
     */
    public function authUserById($id, $user = null)
    {
        if($user === null) {
            $user = User::findFirstById($id);
            if ($user == false) {
                throw new Exception('The user does not exist');
            }
        }
        $this->checkUserFlags($user);
        $this->setSession($user);
        return true;
    }

    /**
     * Get user role.
     * Necessary when user role is dependent on AclHandler type
     *
     * @return string|int
     */
    public function getUserRole()
    {
        $roleId = $this->getUserRoleId();
        if ($this->config->app->aclAdapter == AclHandler::MEMORY) {
            $roles = $this->acl->getRoles();
            foreach ($roles as $key => $role) {
                if ((int)$roleId == $key + 1) {
                    return $role->getName();
                }
            }
            return 'guest';
        }

        return $roleId;
    }

    /**
     * Get User Role Id
     * @return int|\Phalcon\Mvc\Model|\Phalcon\Mvc\Model\Resultset
     */
    public function getUserRoleId()
    {
        $auth = $this->session->get('auth');
        if ($auth) {
            $role = User::getRoleById($auth['id']);
        } else {
            $role = 1;
        }

        return $role;
    }

    /**
     * Get the entity related to user in the active identity
     * @throws \Engine\Mvc\Exception
     * @return \Module\Core\Models\User
     */
    public function getUser()
    {
        $identity = $this->session->get('auth');
        if (!isset($identity['id'])) {
            return false;
        }
        $user = User::findFirstById($identity['id']);
        if ($user == false) {
            throw new Exception('The user does not exist');
        }
        return $user;
    }

    /**
     * Check if the current user can edit an article
     *
     * @return bool
     */
    public function isEditor()
    {
        if ($this->isEditor == null) {
            $this->isEditor = $this->acl->isAllowed($this->getUserRole(), "module:core/admin-content", "edit");
        }
        return $this->isEditor;
    }

    /**
     * Set the return url
     */
    private function setReturnUrl()
    {
        if ($this->request->has('returnUrl')) {
            $returnUrl = $this->request->get('returnUrl', 'string');
            if ($returnUrl != $this->url->get('user/login') || $returnUrl != $this->url->get('user/register')) {
                $this->session->set('returnUrl', $returnUrl);
            }
        }
    }

    /**
     * Redirect to return url
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function redirectReturnUrl() {
        if ($this->session->has('returnUrl')) {
            $returnUrl = $this->session->get('returnUrl');
            $this->session->remove('returnUrl');
        } else {
            $returnUrl = $this->url->get();
        }
        return $this->response->redirect($returnUrl != '/' ? $returnUrl : '', true)->send();
    }

    /**
     * Try to get real ip from headers
     * @return string
     */
    public function getRealIp()
    {
        //Just get the headers if we can or else use the SERVER global
        if ( function_exists( 'apache_request_headers' ) ) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }

        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) &&
            (filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ||
                filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))) {
            $ip = $headers['X-Forwarded-For'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) &&
            (filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ||
                filter_var($headers['X-HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))) {
            $ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
        
        return $ip;
    }

    /**
     * Check if the requested post user is throttled
     * @return bool
     */
    public function isUserRequestThrottled()
    {
        $throttlingCacheKey = $this->getThrottlingCacheKey($this->request->getPost('username', 'alphanum'));
        $throttlingLevel = $this->cache->get($throttlingCacheKey);
        $throttlingLevel = $throttlingLevel != null ? $throttlingLevel : 0;
        if ($throttlingLevel >= self::MAX_LOGIN_TRIES) {
            return true;
        }
        return false;
    }


    /**
     * @param $userName
     * @return string Login try cache key
     */
    private function getThrottlingCacheKey($userName)
    {
        return self::THROTTLING_CACHE_KEY . "-" . $this->getRealIp() . "-$userName";
    }
}