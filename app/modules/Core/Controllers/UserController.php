<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Forms\LoginForm;
use Core\Forms\RegisterForm;
use Core\Models\User;
use Engine\Controller;

/**
 * Class UserController
 * @package Core\Controllers
 */
class UserController extends Controller {

    /**
     * Login user
     */
    public function loginAction() {
        $this->_setTitle('Login');
        $form = new LoginForm();
        $username = $this->request->getPost('username', 'alphanum');
        $password = $this->request->getPost('password');
        $this->view->form = $form;
    }

    /**
     * Register user
     */
    public function registerAction() {
        $this->_setTitle('Register');
        $form = new RegisterForm();
        if ($this->request->isPost()) {
        //    $name = $this->request->getPost('name', array('string', 'striptags'));
            $username = $this->request->getPost('username', 'alphanum');
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');
            if ($password != $repeatPassword) {
                $this->flash->error('Passwords don\'t match');
            }
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($this->security->hash($password));
            $user->setEmail($email);
            $user->setRole(1);
            $user->setStatus(0);
            if (!$user->save()) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $form->clear();
                $this->flash->success('Thanks for sign-up, please log-in to manage your account');
            }
        }
        $this->view->form = $form;
    }
}